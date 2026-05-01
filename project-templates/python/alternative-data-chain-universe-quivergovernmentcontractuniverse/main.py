# region imports
from AlgorithmImports import *
# endregion

class QuiverGovernmentContractChainedUniverseAlgorithm(QCAlgorithm):

    _fundamental = []

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)

        self.universe_settings.resolution = Resolution.DAILY
        # First universe: top 100 US Equities by dollar volume; emits Universe.UNCHANGED.
        self.add_universe(self._fundamental_filter)
        # Second universe: 3+ government contracts totalling over $50K, intersected with the fundamental list.
        self._universe = self.add_universe(QuiverGovernmentContractUniverse, self._select_assets)

        # Rebalance shortly after the open so today's intersection is locked in.
        self.schedule.on(self.date_rules.every_day("SPY"), self.time_rules.at(9, 0, 0), self._rebalance)

    def _fundamental_filter(self, fundamental: List[Fundamental]):
        sorted_by_dollar_volume = sorted(fundamental, key=lambda x: x.dollar_volume, reverse=True)
        self._fundamental = [c.symbol for c in sorted_by_dollar_volume[:100]]
        return Universe.UNCHANGED

    def _select_assets(self, alt_coarse: List[QuiverGovernmentContractUniverse]) -> List[Symbol]:
        # Group by ticker and keep names with 3+ contracts totalling over $50K.
        contracts_by_symbol = {}
        for d in alt_coarse:
            contracts_by_symbol.setdefault(d.symbol, []).append(d)
        alt = [s for s, ds in contracts_by_symbol.items()
               if len(ds) >= 3 and sum(x.amount or 0 for x in ds) > 50000]
        return list(set(self._fundamental) & set(alt))

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return

        weight = 1 / len(self._universe.selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in self._universe.selected]

        self.set_holdings(targets, liquidate_existing_holdings=True)
