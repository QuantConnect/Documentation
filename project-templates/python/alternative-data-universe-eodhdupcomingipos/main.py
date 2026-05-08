# region imports
from AlgorithmImports import *
from QuantConnect.DataSource.EODHD import DealType
# endregion


class EODHDUpcomingIPOsUniverseAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100_000)
        self.settings.seed_initial_prices = True
        self.universe_settings.resolution = Resolution.MINUTE
        # Universe of confirmed non-penny upcoming IPOs.
        self._universe = self.add_universe(EODHDUpcomingIPOs, self._select_assets)
        # Rebalance before market open to trade today's universe.
        self.schedule.on(
            self.date_rules.every_day("SPY"),
            self.time_rules.at(9, 0),
            self._rebalance
        )

    def _select_assets(self, data: List[EODHDUpcomingIPOs]) -> List[Symbol]:
        # Keep expected/priced IPOs with a confirmed date and a >$1 minimum across the price band.
        return [d.symbol for d in data
                if d.ipo_date and d.deal_type in [DealType.EXPECTED, DealType.PRICED] and
                (prices := [x for x in [d.lowest_price, d.highest_price, d.offer_price] if x]) and
                min(prices) > 1]

    def _rebalance(self) -> None:
        if not self._universe.selected:
            return
        weight = 1 / len(self._universe.selected)
        targets = [PortfolioTarget(symbol, weight) for symbol in self._universe.selected]
        self.set_holdings(targets, True)
