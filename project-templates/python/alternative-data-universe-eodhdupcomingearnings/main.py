# region imports
from AlgorithmImports import *
# endregion


class EODHDUpcomingEarningsUniverseAlgorithm(QCAlgorithm):
    _fundamental: list[Symbol] = []

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        # Add a fundamental universe to track the most liquid US equities by dollar volume.
        self.add_universe(self._fundamental_filter)
        # Add an earnings universe restricted to upcoming reporters within the fundamental list.
        self._universe = self.add_universe(EODHDUpcomingEarnings, self._select_assets)
        # Schedule daily rebalancing at 9:00 AM to trade the current universe selection.
        self.schedule.on(
            self.date_rules.every_day("SPY"),
            self.time_rules.at(9, 31),
            self._rebalance
        )

    def _fundamental_filter(self, fundamental: List[Fundamental]) -> Universe.UnchangedUniverse:
        # Store the top 50 equities by dollar volume without changing the active universe.
        self._fundamental = [f.symbol for f in sorted(fundamental, key=lambda f: f.dollar_volume)[-50:]]
        return Universe.UNCHANGED

    def _select_assets(self, data: List[EODHDUpcomingEarnings]) -> List[Symbol]:
        # Filter for symbols with a positive analyst estimate reporting within the next 3 days.
        alt = [d.symbol for d in data
               if d.report_date and d.estimate and
               d.report_date <= self.time + timedelta(3) and d.estimate > 0]
        return [s for s in self._fundamental if s in alt]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return
        # Create an equal weight portfolio with selected securities.
        weight = 1 / len(self._universe.selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in self._universe.selected]
        self.set_holdings(targets, True)
