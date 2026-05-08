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
        self.set_brokerage_model(BrokerageName.BITFINEX, AccountType.CASH)
        # For daily DCA purchases, subscribe to daily asset data.
        coins = ["BTC", "ETH", "LTC"]
        pairs = [self.add_crypto(f"{coin}{self.account_currency}", Resolution.MINUTE) for coin in coins]
        self.set_benchmark(pairs[0])
        self.schedule.on(
            self.date_rules.week_start(),
            self.time_rules.midnight,
            self._rebalance
        )

    def _rebalance(self) -> None:
        targets = [PortfolioTarget(s, .3) for s in self.securities.keys()]
        self.set_holdings(targets, True)
