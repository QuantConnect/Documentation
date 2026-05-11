# region imports
from AlgorithmImports import *
# endregion


class BrainStockRankingChainedUniverseAlgorithm(QCAlgorithm):
    _fundamental: list[Symbol] = []

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        self.universe_settings.resolution = Resolution.MINUTE
        # Select the top 100 US equities by dollar volume for fundamental filtering.
        self.add_universe(self._fundamental_filter)
        # Filter for stocks with positive Brain ML rankings across all horizons.
        self._universe = self.add_universe(BrainStockRankingUniverse, self._select_assets)
        # Rebalance daily before market open to trade the selected universe.
        self.schedule.on(
            self.date_rules.every_day("SPY"),
            self.time_rules.at(9, 0),
            self._rebalance
        )

    def _fundamental_filter(self, fundamental: List[Fundamental]) -> Universe.UnchangedUniverse:
        sorted_by_dollar_volume = sorted(fundamental, key=lambda x: x.dollar_volume)
        self._fundamental = [c.symbol for c in sorted_by_dollar_volume[-100:]]
        return Universe.UNCHANGED

    def _select_assets(self, alt_coarse: List[BrainStockRankingUniverse]) -> List[Symbol]:
        # Keep stocks with positive rankings across all three horizons.
        alt = [d.symbol for d in alt_coarse
               if d.rank_2_days and d.rank_2_days > 0 and
               d.rank_3_days and d.rank_3_days > 0 and
               d.rank_5_days and d.rank_5_days > 0]
        return [s for s in self._fundamental if s in alt]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return
        weight = 1 / len(self._universe.selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in self._universe.selected]
        self.set_holdings(targets, True)
