# region imports
from AlgorithmImports import *
# endregion


class EODHDUpcomingDividendsChainedUniverseAlgorithm(QCAlgorithm):

    _fundamental: List[Fundamental] = []

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        self.universe_settings.resolution = Resolution.MINUTE
        # First universe: store all US Equity fundamentals; emits Universe.UNCHANGED.
        self.add_universe(self._fundamental_filter)
        # Second universe: ex-dividend in the next day with a $0.05+ payout, ranked by dollar volume.
        self._universe = self.add_universe(EODHDUpcomingDividends, self._select_assets)
        # Rebalance before market open to trade today's intersection.
        self.schedule.on(
            self.date_rules.every_day("SPY"),
            self.time_rules.at(9, 0),
            self._rebalance
        )

    def _fundamental_filter(self, fundamental: List[Fundamental]) -> Universe.UnchangedUniverse:
        self._fundamental = fundamental
        return Universe.UNCHANGED

    def _select_assets(self, alt_coarse: List[EODHDUpcomingDividends]) -> List[Symbol]:
        # Keep names with a dividend over $0.05 paying within one day.
        alt = {d.symbol for d in alt_coarse
               if d.dividend_date and d.dividend
               and d.dividend_date <= self.time + timedelta(1) and d.dividend > 0.05}
        self.plot('Universe', 'Raw', len(alt))
        return [c.symbol for c in sorted(
            [c for c in self._fundamental if c.symbol in alt],
            key=lambda c: c.dollar_volume, reverse=True
        )[:100]]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return
        weight = min(1 / len(self._universe.selected), 0.1)
        targets = [PortfolioTarget(symbol, weight) for symbol in self._universe.selected]
        self.set_holdings(targets, True)
