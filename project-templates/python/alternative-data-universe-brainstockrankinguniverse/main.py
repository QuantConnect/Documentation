# region imports
from AlgorithmImports import *
# endregion


class BrainStockRankingUniverseAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        self.universe_settings.resolution = Resolution.DAILY
        # Universe of US Equities with positive Brain ML rankings across 2-, 3-, and 5-day horizons.
        self._universe = self.add_universe(BrainStockRankingUniverse, self._select_assets)
        # Rebalance shortly after the open so today's universe is locked in.
        self.schedule.on(
            self.date_rules.every_day("SPY"),
            self.time_rules.at(9, 0),
            self._rebalance
        )

    def _select_assets(self, data: List[BrainStockRankingUniverse]) -> List[Symbol]:
        # Keep names with consistent positive momentum across all three horizons.
        return [d.symbol for d in data
                if d.rank_2_days and d.rank_2_days > 0 and
                d.rank_3_days and d.rank_3_days > 0 and
                d.rank_5_days and d.rank_5_days > 0]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return
        weight = 1 / len(self._universe.selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in self._universe.selected]
        self.set_holdings(targets, True)
