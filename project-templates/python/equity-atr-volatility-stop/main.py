# region imports
from AlgorithmImports import *
# endregion

class AtrChandelierTrailingStopAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self.set_start_date(2022, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        self.settings.automatic_indicator_warm_up = True

        self._spy = self.add_equity("SPY", Resolution.MINUTE)
        self._atr = self.atr(self._spy.symbol, 14)
        # Alternatively, use a manual indicator.
        # self._atr = AverageTrueRange(14)
        # self.warm_up_indicator(self._spy, self._atr)
        # self.register_indicator(self._spy, self._atr)
        self._trailing_high: float = 0.0
        self._waiting_to_reenter = False

        self.plot("Stop", "ATR Stop", 0)
        self.plot("Stop", "Trailing High", 0)

    def on_data(self, data: Slice) -> None:
        if not self._atr.is_ready:
            return

        bar = data.bars.get(self._spy.symbol)
        if bar is None:
            return

        close = bar.close

        if self._waiting_to_reenter:
            self.set_holdings(self._spy.symbol, 1.0)
            self._trailing_high = close
            self._waiting_to_reenter = False
            return

        if not self.portfolio.invested:
            self.set_holdings(self._spy.symbol, 1.0)
            self._trailing_high = close
            return

        if close > self._trailing_high:
            self._trailing_high = close

        atr_value = self._atr.current.value
        stop_level = self._trailing_high - 3.0 * atr_value

        self.plot("Stop", "ATR Stop", stop_level)
        self.plot("Stop", "Trailing High", self._trailing_high)

        if close < stop_level:
            self.liquidate(self._spy.symbol)
            self._waiting_to_reenter = True
            self._trailing_high = 0.0
