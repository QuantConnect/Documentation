# region imports
from AlgorithmImports import *
# endregion


class ForexExampleAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        # Subscribe to each Forex pair.
        pairs = ["EURUSD", "USDJPY", "USDCAD"]
        for pair in pairs:
            forex = self.add_forex(pair)
            # Create a Minimum indicator to track the lowest bid-ask spread for the past 12 hours.
            forex.spread_low = Minimum(12*60)
            # Warm up the indicator so it's immediately ready to use.
            for quote_bar in self.history[QuoteBar](forex.symbol, 12*60):
                forex.spread_low.update(quote_bar.end_time, quote_bar.ask.close - quote_bar.bid.close)

    def on_data(self, data: Slice) -> None:
        # Iterate over available quote bars to evaluate spread conditions.
        for symbol, quote_bar in data.quote_bars.items():
            # Bid-ask spread = Ask price - Bid price.
            bid_ask_spread = round(quote_bar.ask.close - quote_bar.bid.close, 6)
            # Update the spread minimum indicator to calculate the lowest bid-ask spread over the last 12 hours.
            forex = self.securities[symbol]
            forex.spread_low.update(quote_bar.end_time, bid_ask_spread)
            # Enter long when the spread equals the 12-hour minimum, indicating peak liquidity.
            if not forex.invested and bid_ask_spread == forex.spread_low.current.value:
                self.market_order(forex, 1000)
