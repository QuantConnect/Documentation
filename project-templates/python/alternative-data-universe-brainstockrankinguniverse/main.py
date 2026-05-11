# region imports
from AlgorithmImports import *
# endregion


class BrainStockRankingUniverseAlgorithm(QCAlgorithm):
    _fundamental: list[Symbol] = []

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        # Add a fundamental universe to track the most liquid US equities by dollar volume.
        self.add_universe(self._fundamental_filter)
        # Add a Brain Stock Ranking universe, restricted to high-ranking names within the fundamental list.
        self._universe = self.add_universe(BrainStockRankingUniverse, self._select_assets)
        # Schedule daily rebalancing at 9:00 AM before market open.
        self.schedule.on(
            self.date_rules.every_day("SPY"), 
            self.time_rules.at(9, 0), 
            self._rebalance
        )

    def _fundamental_filter(self, fundamental: List[Fundamental]) -> Universe.UnchangedUniverse:
        # Store the top 50 symbols by dollar volume for use in the alt-data universe.
        self._fundamental = [f.symbol for f in sorted(fundamental, key=lambda f: f.dollar_volume)[-50:]]
        return Universe.UNCHANGED

    def _select_assets(self, data: List[BrainStockRankingUniverse]) -> List[Symbol]:
        # Filter for stocks with positive rankings across 2-day, 3-day, and 5-day horizons.
        alt = [d.symbol for d in data
               if d.rank_2_days and d.rank_2_days > 0 and
               d.rank_3_days and d.rank_3_days > 0 and
               d.rank_5_days and d.rank_5_days > 0]
        return [s for s in self._fundamental if s in alt]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return
        # Calculate equal weights for all selected securities.
        weight = 1 / len(self._universe.selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in self._universe.selected]
        self.set_holdings(targets, True)
