# region imports
from AlgorithmImports import *
# endregion

class ChaikinMoneyFlowXlfAlgorithm(QCAlgorithm):
    _long_threshold = 0.10
    _short_threshold = -0.10

    def initialize(self) -> None:
        self.set_start_date(2022, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)

        # automatic_indicator_warm_up only supports automatic indicators, not manual indicators.
        self.settings.automatic_indicator_warm_up = True

        self._xlf = self.add_equity("XLF", Resolution.MINUTE)
        self._cmf = self.cmf(self._xlf.symbol, 20, Resolution.MINUTE)
        # Alternatively, use a manual indicator.
        # self._cmf = ChaikinMoneyFlow(20)
        # self.warm_up_indicator(self._xlf, self._cmf)
        # self.register_indicator(self._xlf, self._cmf)
        self.plot_indicator("CMF", self._cmf)

        self._previous_signal = 0  # 1 = long, -1 = short, 0 = flat

        self.schedule.on(
            self.date_rules.every_day(self._xlf.symbol),
            self.time_rules.after_market_open(self._xlf.symbol, 30),
            self._rebalance
        )

    def _rebalance(self) -> None:
        if not self._cmf.is_ready:
            return

        cmf_value = self._cmf.current.value
        signal = 0
        if cmf_value > self._long_threshold:
            signal = 1
        elif cmf_value < self._short_threshold:
            signal = -1

        if signal == self._previous_signal:
            return

        self._previous_signal = signal

        if signal == 1:
            self.set_holdings(self._xlf.symbol, 1.0)
        elif signal == -1:
            self.set_holdings(self._xlf.symbol, -1.0)
        else:
            self.liquidate(self._xlf.symbol)
