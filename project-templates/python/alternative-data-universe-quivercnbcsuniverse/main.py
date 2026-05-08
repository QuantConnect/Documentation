# region imports
from AlgorithmImports import *
# endregion


class QuiverCNBCsUniverseAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        # Trade daily on CNBC opinion updates.
        self.universe_settings.resolution = Resolution.DAILY
        # Universe of US Equities flagged by 3+ positive CNBC opinions.
        self._universe = self.add_universe(QuiverCNBCsUniverse, self._select_assets)
        # Rebalance shortly after the open so today's universe is locked in.
        self.schedule.on(
            self.date_rules.every_day("SPY"),
            self.time_rules.at(9, 0),
            self._rebalance
        )

    def _select_assets(self, data: List[QuiverCNBCsUniverse]) -> List[Symbol]:
        # Group raw CNBC opinions by ticker so we can score each name.
        cnbc_by_symbol: dict[Symbol, list[QuiverCNBCsUniverse]] = {}
        for d in data:
            cnbc_by_symbol.setdefault(d.symbol, []).append(d)
        # Keep names with 3+ BUY recommendations to filter out noise.
        return [s for s, ds in cnbc_by_symbol.items()
                if sum(1 for d in ds if d.direction == OrderDirection.BUY) >= 3]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return
        weight = 1 / len(self._universe.selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in self._universe.selected]
        self.set_holdings(targets, True)
