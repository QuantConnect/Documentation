# region imports
from AlgorithmImports import *
# endregion


class CryptoExampleAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        # Set the account current to USDT as we will trade USDT quoted coins.
        self.set_account_currency("USDT", 100000)
        # Set the brokerage and account type to match your brokerage environment for accurate fee and margin behavior.
        self.set_brokerage_model(BrokerageName.BINANCE, AccountType.MARGIN)
        # For daily DCA purchases, subscribe to daily asset data.
        coins = ["BTC", "ETH", "LTC"]
        pairs = [self.add_crypto_future(f"{coin}{self.account_currency}") for coin in coins]
        self.set_benchmark(pairs[0])
        self.schedule.on(
            self.date_rules.week_start(),
            self.time_rules.midnight,
            self._rebalance
        )

    def on_data(self, data: Slice) -> None:
        for symbol, margin_interest_rate in data.margin_interest_rates.items():
            self.plot(symbol.value, "Interest", margin_interest_rate.interest_rate)

    def _rebalance(self) -> None:
        targets = [PortfolioTarget(s, .3) for s in self.securities.keys()]
        self.set_holdings(targets, True)
