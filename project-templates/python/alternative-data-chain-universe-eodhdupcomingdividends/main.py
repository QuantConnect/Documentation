# region imports
from AlgorithmImports import *
# endregion


class EODHDUpcomingDividendsChainedUniverseAlgorithm(QCAlgorithm):
    _fundamental: list[Symbol] = []

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        self.universe_settings.resolution = Resolution.MINUTE
        # Add a Fundamental Universe selecting the top 100 US Equities by dollar volume.
        self.add_universe(self._fundamental_filter)
        # Add an EODHD Universe for upcoming dividends intersected with the fundamental list.
        self._universe = self.add_universe(EODHDUpcomingDividends, self._select_assets)
        # Schedule daily rebalancing at 9 AM to trade the universe intersection.
        self.schedule.on(
            self.date_rules.every_day("SPY"),
            self.time_rules.at(9, 0),
            self._rebalance
        )

    def _fundamental_filter(self, fundamental: List[Fundamental]) -> Universe.UnchangedUniverse:
        sorted_by_dollar_volume = sorted(fundamental, key=lambda x: x.dollar_volume)
        self._fundamental = [c.symbol for c in sorted_by_dollar_volume[-100:]]
        return Universe.UNCHANGED

    def _select_assets(self, alt_coarse: List[EODHDUpcomingDividends]) -> List[Symbol]:
        # Filter for assets with dividends over $0.05 paying within one day.
        alt = [d.symbol for d in alt_coarse
               if d.dividend_date and d.dividend and
               d.dividend_date <= self.time + timedelta(1) and d.dividend > 0.05]
        return [s for s in self._fundamental if s in alt]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return
        weight = 1 / len(self._universe.selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in self._universe.selected]
        self.set_holdings(targets, True)
