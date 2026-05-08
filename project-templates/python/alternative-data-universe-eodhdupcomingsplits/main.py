# region imports
from AlgorithmImports import *
# endregion


class EODHDUpcomingSplitsUniverseAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        self.universe_settings.resolution = Resolution.MINUTE
        # Universe of US Equities with a forward stock split in the next 3 days.
        self._universe = self.add_universe(EODHDUpcomingSplits, self._select_assets)
        # Rebalance shortly after the open so today's universe is locked in.
        self.schedule.on(
            self.date_rules.every_day("SPY"),
            self.time_rules.at(9, 0),
            self._rebalance
        )

    def _select_assets(self, data: List[EODHDUpcomingSplits]) -> List[Symbol]:
        # Keep names with a forward split (factor > 1) within 3 days.
        return [d.symbol for d in data
                if d.split_date and d.split_factor and
                d.split_date <= self.time + timedelta(3) and d.split_factor > 1]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return
        weight = 1 / len(self._universe.selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in self._universe.selected]
        self.set_holdings(targets, True)
