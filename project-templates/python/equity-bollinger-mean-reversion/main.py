# region imports
from AlgorithmImports import *
# endregion

class EquityBollingerMeanReversionAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        self.settings.seed_initial_prices = True
        self.settings.automatic_indicator_warm_up = True
        self._equity = self.add_equity("SPY")
        self._bb = self.bb(self._equity, 20, 2, resolution=Resolution.DAILY)
        # Alternatively, use a manual indicator.
        # self._bb = BollingerBands(20, 2)
        # self.warm_up_indicator(self._equity, self._bb, Resolution.DAILY)
        # self.register_indicator(self._equity, self._bb, Resolution.DAILY)
        self.plot_indicator("SPY", self._bb.upper_band, self._bb.middle_band, self._bb.lower_band)
        self.schedule.on(self.date_rules.every_day(self._equity), self.time_rules.at(8, 0), self._rebalance)

    def _rebalance(self) -> None:
        if not self.portfolio.invested and self._equity.price < self._bb.lower_band.current.value:
            self.set_holdings(self._equity, 1)
        elif self.portfolio.invested and self._equity.price >= self._bb.middle_band.current.value:
            self.liquidate()
