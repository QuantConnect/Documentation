# region imports
from AlgorithmImports import *
# endregion

class EquityRsiOverboughtOversoldAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        # automatic_indicator_warm_up only supports automatic indicators, not manual indicators.
        self.settings.automatic_indicator_warm_up = True
        self._equity = self.add_equity("QQQ", Resolution.HOUR)
        self._rsi = self.rsi(self._equity, 14, MovingAverageType.WILDERS, Resolution.HOUR)
        # Alternatively, use a manual indicator.
        # self._rsi = RelativeStrengthIndex(14, MovingAverageType.WILDERS)
        # self.warm_up_indicator(self._equity, self._rsi)
        # self.register_indicator(self._equity, self._rsi)
        self.plot_indicator("RSI", self._rsi)

    def on_data(self, data: Slice) -> None:
        if not self._rsi.is_ready:
            return
        rsi = self._rsi.current.value
        quantity = self._equity.holdings.quantity
        if quantity <= 0 and rsi < 30:
            self.set_holdings(self._equity, 1)
        elif quantity > 0 and rsi > 50:
            self.liquidate()
        elif quantity >= 0 and rsi > 70:
            self.set_holdings(self._equity, -1)
        elif quantity < 0 and rsi < 50:
            self.liquidate()
