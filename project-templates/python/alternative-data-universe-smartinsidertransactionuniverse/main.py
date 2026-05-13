# region imports
from AlgorithmImports import *
# endregion

class SmartInsiderTransactionUniverseAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        self.settings.seed_initial_prices = True

        self.universe_settings.resolution = Resolution.DAILY
        # Universe of large-cap US Equities executing meaningful share buybacks.
        self._universe = self.add_universe(SmartInsiderTransactionUniverse, self._select_assets)

        # Rebalance shortly after the open so today's universe is locked in.
        self.schedule.on(self.date_rules.every_day("SPY"), self.time_rules.at(9, 0, 0), self._rebalance)

    def _select_assets(self, data: List[SmartInsiderTransactionUniverse]) -> List[Symbol]:
        # Keep $100M+ market-cap names buying back over 0.5% of shares.
        return [d.symbol for d in data
                if d.buyback_percentage and d.usd_market_cap
                and d.buyback_percentage > 0.005 and d.usd_market_cap > 100000000]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return
        securities = [s for s in self._universe.selected if self.securities[s].price]
        if not securities:
            return
        weight = min(1 / len(securities), 0.1)
        targets = [PortfolioTarget(symbol, weight) for symbol in securities]

        self.set_holdings(targets, liquidate_existing_holdings=True)
