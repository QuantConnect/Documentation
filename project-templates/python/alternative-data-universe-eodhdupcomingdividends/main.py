# region imports
from AlgorithmImports import *
# endregion

class EODHDUpcomingDividendsUniverseAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        self.settings.seed_initial_prices = True
        self.universe_settings.resolution = Resolution.HOUR
        # Universe of US Equities going ex-dividend in the next day with a meaningful payout.
        self._universe = self.add_universe(EODHDUpcomingDividends, self._select_assets)

        # Rebalance shortly after the open so today's universe is locked in.
        self.schedule.on(self.date_rules.every_day("SPY"), self.time_rules.at(9, 0, 0), self._rebalance)

    def _select_assets(self, data: List[EODHDUpcomingDividends]) -> List[Symbol]:
        # Keep names with a dividend over $0.75 paying within one day.
        return [d.symbol for d in data
                if d.dividend_date and d.dividend
                and d.dividend_date <= self.time + timedelta(1) and d.dividend > 0.75]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return
        securities = [s for s in self._universe.selected if self.securities[s].price]

        weight = 1 / len(securities)
        targets = [PortfolioTarget(symbol, weight) for symbol in securities]

        self.set_holdings(targets, liquidate_existing_holdings=True)
