# region imports
from AlgorithmImports import *
# endregion


class QuiverQuantCongressChainedUniverseAlgorithm(QCAlgorithm):

    _fundamental: List[Fundamental] = []

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        self.universe_settings.resolution = Resolution.MINUTE
        # First universe: store all US Equity fundamentals; emits Universe.UNCHANGED.
        self.add_universe(self._fundamental_filter)
        # Second universe: US Congress BUY disclosures over $200K, ranked by dollar volume.
        self._universe = self.add_universe(QuiverQuantCongressUniverse, self._select_assets)
        # Rebalance before market open to trade today's intersection.
        self.schedule.on(
            self.date_rules.every_day("SPY"),
            self.time_rules.at(9, 0),
            self._rebalance
        )

    def _fundamental_filter(self, fundamental: List[Fundamental]) -> Universe.UnchangedUniverse:
        self._fundamental = fundamental
        return Universe.UNCHANGED

    def _select_assets(self, alt_coarse: List[QuiverQuantCongressUniverse]) -> List[Symbol]:
        # Keep buy disclosures over $200K to filter out small reports.
        alt = {d.symbol for d in alt_coarse
               if d.amount and d.amount > 200000 and
               d.transaction == OrderDirection.BUY}
        self.plot('Universe', 'Raw', len(alt))
        # Among the matches, keep the 100 most liquid by dollar volume.
        return [c.symbol for c in sorted(
            [c for c in self._fundamental if c.symbol in alt],
            key=lambda c: c.dollar_volume, reverse=True
        )[:100]]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return
        weight = 1 / len(self._universe.selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in self._universe.selected]
        self.set_holdings(targets, True)
