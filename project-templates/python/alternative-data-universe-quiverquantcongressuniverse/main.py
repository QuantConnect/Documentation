# region imports
from AlgorithmImports import *
# endregion

class QuiverQuantCongressUniverseAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        self.settings.seed_initial_prices = True

        self.universe_settings.resolution = Resolution.DAILY
        # Universe of US Equities recently bought by US Congress members in trades over $200K.
        self._universe = self.add_universe(QuiverQuantCongressUniverse, self._select_assets)

        # Rebalance shortly after the open so today's universe is locked in.
        self.schedule.on(self.date_rules.every_day("SPY"), self.time_rules.at(9, 0, 0), self._rebalance)

    def _select_assets(self, data: List[QuiverQuantCongressUniverse]) -> List[Symbol]:
        # Aggregate insider buy volume per ticker and keep names buying $200K+.
        spend_by_symbol: dict[Symbol, float] = {}
        for d in data:
            if d.transaction == OrderDirection.BUY and d.amount:
                spend_by_symbol[d.symbol] = spend_by_symbol.get(d.symbol, 0) + d.amount
        return [s for s, v in spend_by_symbol.items() if v > 200000]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return

        weight = 1 / len(self._universe.selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in self._universe.selected]

        self.set_holdings(targets, liquidate_existing_holdings=True)
