# region imports
from AlgorithmImports import *
# endregion

class EODHDUpcomingEarningsUniverseAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        self.settings.seed_initial_prices = True
        self.universe_settings.resolution = Resolution.HOUR
        # Universe of US Equities reporting earnings in the next 3 days with estimate > 1.5.
        self._universe = self.add_universe(EODHDUpcomingEarnings, self._select_assets)

        # Rebalance shortly after the open so today's universe is locked in.
        self.schedule.on(self.date_rules.every_day("SPY"), self.time_rules.at(9, 31, 0), self._rebalance)

    def _select_assets(self, data: List[EODHDUpcomingEarnings]) -> List[Symbol]:
        # Keep names with an analyst estimate over 1.5 ahead of the report.
        return [d.symbol for d in data
                if d.report_date and d.estimate and
                d.report_date <= self.time + timedelta(3) and d.estimate > 1.5]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return
        securities = [s for s in self._universe.selected if self.securities[s].price]
        if not securities:
            return
        weight = 1 / len(securities)
        targets = [PortfolioTarget(symbol, weight) for symbol in securities]

        self.set_holdings(targets, liquidate_existing_holdings=True)
