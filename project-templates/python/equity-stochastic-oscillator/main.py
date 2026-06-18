# region imports
from AlgorithmImports import *
# endregion

class EquityStochasticOscillatorAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        # automatic_indicator_warm_up only supports automatic indicators, not manual indicators.
        self.settings.automatic_indicator_warm_up = True
        self._equity = self.add_equity("IWM")
        self._stoch = self.sto(self._equity, 14, 3, 3, Resolution.DAILY)
        # Alternatively, use a manual indicator.
        # self._stoch = Stochastic(14, 3, 3)
        # self.warm_up_indicator(self._equity, self._stoch, Resolution.DAILY)
        # self.register_indicator(self._equity, self._stoch, Resolution.DAILY)

    def on_data(self, data: Slice) -> None:
        if not self._stoch.is_ready:
            return
        k = self._stoch.stoch_k.current.value
        d = self._stoch.stoch_d.current.value
        prev_k = self._stoch.stoch_k.previous.value
        prev_d = self._stoch.stoch_d.previous.value
        crossed_up = prev_k <= prev_d and k > d
        crossed_down = prev_k >= prev_d and k < d
        quantity = self._equity.holdings.quantity
        if quantity == 0 and crossed_up and d < 20:
            self.set_holdings(self._equity, 1)
        elif quantity > 0 and crossed_down and d > 80:
            self.liquidate()
