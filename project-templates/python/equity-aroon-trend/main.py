# region imports
from AlgorithmImports import *
# endregion

class AroonOscillatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self.set_start_date(2022, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)

        self.settings.automatic_indicator_warm_up = True

        self._spy = self.add_equity("SPY", Resolution.MINUTE).symbol

        self._aroon = self.aroon(self._spy, 25, Resolution.MINUTE)
        # Alternatively, use a manual indicator.
        # self._aroon = AroonOscillator(25, 25)
        # self.warm_up_indicator(self._spy, self._aroon)
        # self.register_indicator(self._spy, self._aroon)

        self.plot_indicator("Aroon Oscillator", self._aroon)

    def on_data(self, data: Slice) -> None:
        if not self._aroon.is_ready:
            return

        current = self._aroon.current.value
        previous = self._aroon.previous.value

        if self.portfolio[self._spy].is_long:
            if previous >= -50 and current < -50:
                self.liquidate(self._spy)
        elif previous <= 50 and current > 50:
            self.set_holdings(self._spy, 1.0)
