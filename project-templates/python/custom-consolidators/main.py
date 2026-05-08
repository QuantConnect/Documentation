# region imports
from AlgorithmImports import *
# endregion


class CalendarConsolidatorExampleAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        # Add the USDJPY Forex pair.
        self._pair = self.add_forex("USDJPY")
        # Create an EMA indicator for the pair.
        self._pair.ema = ExponentialMovingAverage(10)
        # Create a QuoteBar consolidator with a custom consolidation period.
        consolidator = QuoteBarConsolidator(self._daily_forex_consolidation_period)
        # You can also create a consolidator with a period of one day and start time of 17.
        # Consolidator = QuoteBarConsolidator(timedelta(1), timedelta(hours=17)).
        # Attach a consolidation handler that will receive the consolidated bars.
        consolidator.data_consolidated += self._consolidation_handler
        # Subscribe the consolidator for automatic updates with the prices of the pair.
        self.subscription_manager.add_consolidator(self._pair, consolidator)
        # Register the indicator for automatic updates with the consolidated bars.
        self.register_indicator(self._pair, self._pair.ema, consolidator)
        # Warm up the consolidator and indicator.
        history = self.history[QuoteBar](self._pair.symbol, 29000, Resolution.MINUTE)
        for bar in history:
            consolidator.update(bar)

    # Define the consolidation period.
    def _daily_forex_consolidation_period(self, dt: datetime) -> CalendarInfo:
        # Set the start of the bar to be 5 PM ET.
        start = dt.replace(hour=0, minute=0, second=0, microsecond=0)
        if dt.hour < 17:
            start -= timedelta(hours=7)
        else:
            start += timedelta(hours=17)
        # Set the end of the bar to be 5 PM ET the next day.
        return CalendarInfo(start, timedelta(1))

    def _consolidation_handler(self, sender: object, consolidated_bar: QuoteBar) -> None:
        # Wait until the indicator is ready and the algorithm is running.
        if not self._pair.ema.is_ready or self.is_warming_up:
            return
        # Plot the closing price and the EMA.
        self.plot(consolidated_bar.symbol.value, 'Close', consolidated_bar.close)
        self.plot(consolidated_bar.symbol.value, 'EMA', self._pair.ema.current.value)
        if not self._pair.holdings.is_long and consolidated_bar.close > self._pair.ema.current.value:
            self.set_holdings(self._pair, 1)
        if consolidated_bar.close < self._pair.ema.current.value:
            self.liquidate()
