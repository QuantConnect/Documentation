# region imports
from AlgorithmImports import *
# endregion


class EODHDUpcomingIPOsChainedUniverseAlgorithm(QCAlgorithm):

    _fundamental: List[Fundamental] = []
    # Map of IPO symbol -> IPO date, captured while the event is upcoming so we can
    # trade the name once Morningstar has a few days of fundamentals on it.
    _ipo_dates: Dict[Symbol, datetime] = {}

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        self.universe_settings.resolution = Resolution.MINUTE
        # First universe: store all US Equity fundamentals; emits Universe.UNCHANGED.
        self.add_universe(self._fundamental_filter)
        # Second universe: trade IPOs one week after listing so fundamentals are populated.
        self._universe = self.add_universe(EODHDUpcomingIPOs, self._select_assets)
        # Rebalance before market open to trade today's intersection.
        self.schedule.on(
            self.date_rules.every_day("SPY"),
            self.time_rules.at(9, 0),
            self._rebalance
        )

    def _fundamental_filter(self, fundamental: List[Fundamental]) -> Universe.UnchangedUniverse:
        self._fundamental = fundamental
        return Universe.UNCHANGED

    def _select_assets(self, alt_coarse: List[EODHDUpcomingIPOs]) -> List[Symbol]:
        # Capture expected/priced IPOs with a confirmed date and a >$1 minimum price band.
        for d in alt_coarse:
            if (d.ipo_date and d.deal_type in [EODHD.DealType.EXPECTED, EODHD.DealType.PRICED] and
                (prices := [x for x in [d.lowest_price, d.highest_price, d.offer_price] if x]) and
                min(prices) > 1):
                self._ipo_dates[d.symbol] = d.ipo_date
        # Drop entries whose IPO was more than 30 days ago to keep the dict bounded.
        self._ipo_dates = {s: d for s, d in self._ipo_dates.items()
                           if d > self.time - timedelta(30)}
        # Trade IPOs that listed at least 7 days ago, ranked by dollar volume.
        alt = {s for s, d in self._ipo_dates.items()
               if d <= self.time - timedelta(7)}
        self.plot('Universe', 'Raw', len(alt))
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
