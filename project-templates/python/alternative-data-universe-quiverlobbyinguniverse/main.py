# region imports
from AlgorithmImports import *
# endregion


class QuiverLobbyingUniverseAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        self.universe_settings.resolution = Resolution.MINUTE
        # Universe of US Equities with material corporate lobbying spend.
        self._universe = self.add_universe(QuiverLobbyingUniverse, "QuiverLobbyingUniverse", Resolution.DAILY, self._select_assets)
        # Rebalance shortly after the open so today's universe is locked in.
        self.schedule.on(
            self.date_rules.every_day("SPY"),
            self.time_rules.at(9, 0),
            self._rebalance
        )

    def _select_assets(self, data: List[QuiverLobbyingUniverse]) -> List[Symbol]:
        # Aggregate lobbying spend per ticker and keep names spending $100K+.
        spend_by_symbol: dict[Symbol, float] = {}
        for d in data:
            spend_by_symbol[d.symbol] = spend_by_symbol.get(d.symbol, 0) + (d.amount or 0)
        return [s for s, v in spend_by_symbol.items() if v >= 100000]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return
        weight = 1 / len(self._universe.selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in self._universe.selected]
        self.set_holdings(targets, True)
