# region imports
from AlgorithmImports import *
# endregion

class LargeCapCryptoUniverseAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.settings.seed_initial_prices = True
        # Set the account currency to USDT
        self.set_account_currency("USDT") 
        self.set_brokerage_model(BrokerageName.COINBASE, AccountType.CASH)

        def selector(data: List[CryptoUniverse]) -> List[Symbol]:
            selected = [x for x in data if x.volume_in_usd and x.symbol.value.endswith(self.account_currency)]
            selected = sorted(selected, key=lambda x: x.volume_in_usd, reverse=True)[:10]
            return [x.symbol for x in selected]

        # Add the Crypto universe and define the selection function.
        self._universe = self.add_universe(CryptoUniverse.coinbase(selector))

        # Add a Sheduled Event to rebalance the portfolio.
        self.schedule.on(self.date_rules.every_day(), self.time_rules.at(12, 0), self._rebalance)

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return
        pairs = [self.securities[symbol] for symbol in self._universe.selected]
        # Form an equal weighted portfolio of the coins in the universe.
        targets = [PortfolioTarget(pair, 0.5/len(pairs)) for pair in pairs]
        # Place orders to rebalance the portfolio.
        self.set_holdings(targets, True)
