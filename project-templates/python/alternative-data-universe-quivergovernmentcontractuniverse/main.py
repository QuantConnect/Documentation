# region imports
from AlgorithmImports import *
# endregion


class QuiverGovernmentContractUniverseAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        self.universe_settings.resolution = Resolution.DAILY
        # Universe of US Equities with frequent, sizable US government contracts.
        self._universe = self.add_universe(QuiverGovernmentContractUniverse, self._select_assets)
        # Rebalance shortly after the open so today's universe is locked in.
        self.schedule.on(
            self.date_rules.every_day("SPY"),
            self.time_rules.at(9, 0),
            self._rebalance
        )

    def _select_assets(self, data: List[QuiverGovernmentContractUniverse]) -> List[Symbol]:
        # Group by ticker and keep names with 3+ contracts totalling over $50K.
        contracts_by_symbol: dict[Symbol, list[QuiverGovernmentContractUniverse]] = {}
        for d in data:
            contracts_by_symbol.setdefault(d.symbol, []).append(d)
        return [s for s, ds in contracts_by_symbol.items()
                if len(ds) >= 3 and sum(x.amount or 0 for x in ds) > 50000]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return
        weight = 1 / len(self._universe.selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in self._universe.selected]
        self.set_holdings(targets, True)
