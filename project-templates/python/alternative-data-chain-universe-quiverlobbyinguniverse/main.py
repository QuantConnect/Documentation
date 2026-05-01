# region imports
from AlgorithmImports import *
# endregion

class QuiverLobbyingChainedUniverseAlgorithm(QCAlgorithm):

    _fundamental = []

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)

        self.universe_settings.resolution = Resolution.DAILY
        # First universe: top 100 US Equities by dollar volume; emits Universe.UNCHANGED.
        self.add_universe(self._fundamental_filter)
        # Second universe: $100K+ corporate lobbying spend, intersected with the fundamental list.
        self._universe = self.add_universe(QuiverLobbyingUniverse, "QuiverLobbyingUniverse",
                                           Resolution.DAILY, self._select_assets)

        # Rebalance shortly after the open so today's intersection is locked in.
        self.schedule.on(self.date_rules.every_day("SPY"), self.time_rules.at(9, 0, 0), self._rebalance)

    def _fundamental_filter(self, fundamental: List[Fundamental]):
        sorted_by_dollar_volume = sorted(fundamental, key=lambda x: x.dollar_volume, reverse=True)
        self._fundamental = [c.symbol for c in sorted_by_dollar_volume[:100]]
        return Universe.UNCHANGED

    def _select_assets(self, alt_coarse: List[QuiverLobbyingUniverse]) -> List[Symbol]:
        # Aggregate lobbying spend per ticker and keep names spending $100K+.
        spend_by_symbol = {}
        for d in alt_coarse:
            spend_by_symbol[d.symbol] = spend_by_symbol.get(d.symbol, 0) + (d.amount or 0)
        alt = [s for s, v in spend_by_symbol.items() if v >= 100000]
        return list(set(self._fundamental) & set(alt))

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return

        weight = 1 / len(self._universe.selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in self._universe.selected]

        self.set_holdings(targets, liquidate_existing_holdings=True)
