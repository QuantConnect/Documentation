# region imports
from AlgorithmImports import *
# endregion


class EODHDUpcomingEarningsUniverseAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        self.universe_settings.resolution = Resolution.DAILY
        # Universe of US Equities reporting earnings in the next 3 days with a positive estimate.
        self._universe = self.add_universe(EODHDUpcomingEarnings, self._select_assets)
        # Rebalance shortly after the open so today's universe is locked in.
        self.schedule.on(
            self.date_rules.every_day("SPY"),
            self.time_rules.at(9, 0),
            self._rebalance
        )

    def _select_assets(self, data: List[EODHDUpcomingEarnings]) -> List[Symbol]:
        # Keep names with a positive analyst estimate ahead of the report.
        return [d.symbol for d in data
                if d.report_date and d.estimate and
                d.report_date <= self.time + timedelta(3) and d.estimate > 0]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return
        weight = 1 / len(self._universe.selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in self._universe.selected]
        self.set_holdings(targets, True)
