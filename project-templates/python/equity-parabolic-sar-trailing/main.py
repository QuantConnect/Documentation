# region imports
from AlgorithmImports import *
# endregion

class EquityParabolicSarTrailingTemplateAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        self.settings.automatic_indicator_warm_up = True
        self._equity = self.add_equity("SPY")
        self._psar = self.psar(self._equity, 0.02, 0.02, 0.2, Resolution.DAILY)
        # Alternatively, use a manual indicator.
        # self._psar = ParabolicStopAndReverse(0.02, 0.02, 0.2)
        # self.warm_up_indicator(self._equity.symbol, self._psar, Resolution.DAILY)
        # self.register_indicator(self._equity.symbol, self._psar, Resolution.DAILY)
        self.plot_indicator("SPY", self._psar)

    def on_data(self, data: Slice) -> None:
        if not data.bars or not self._psar.is_ready:
            return
        price = self._equity.price
        sar = self._psar.current.value
        self.plot('SPY', 'Price', price)
        if not self._equity.holdings.invested and sar < price:
            self.set_holdings(self._equity, 1)
        elif self._equity.holdings.invested and sar > price:
            self.liquidate()
