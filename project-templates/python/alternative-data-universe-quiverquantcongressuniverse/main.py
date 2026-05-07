# region imports
from AlgorithmImports import *
# endregion

class QuiverQuantCongressUniverseAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)

        self.universe_settings.resolution = Resolution.DAILY
        # Universe of US Equities recently bought by US Congress members in trades over $200K.
        self._universe = self.add_universe(QuiverQuantCongressUniverse, self._select_assets)

        # Rebalance shortly after the open so today's universe is locked in.
        self.schedule.on(self.date_rules.every_day("SPY"), self.time_rules.at(9, 0, 0), self._rebalance)

    def _select_assets(self, data: List[QuiverQuantCongressUniverse]) -> List[Symbol]:
        # Keep buy disclosures over $200K to filter out small reports.
        return [d.symbol for d in data
                if d.amount and d.amount > 200000
                and d.transaction == OrderDirection.BUY]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return

        weight = 1 / len(self._universe.selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in self._universe.selected]

        self.set_holdings(targets, liquidate_existing_holdings=True)
