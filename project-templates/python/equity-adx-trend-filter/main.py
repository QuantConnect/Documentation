# region imports
from AlgorithmImports import *
# endregion

class EquityADXTrendFilterAlgorithm(QCAlgorithm):
    _adx_threshold = 25

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        # automatic_indicator_warm_up only supports automatic indicators, not manual indicators.
        self.settings.automatic_indicator_warm_up = True
        self._spy = self.add_equity("SPY")
        self._trend = IndicatorExtensions.minus(
            self.ema(self._spy, 50, Resolution.DAILY),
            self.ema(self._spy, 200, Resolution.DAILY)
        )
        # Alternatively, use a manual indicator.
        # self._ema_fast = ExponentialMovingAverage(50)
        # self.warm_up_indicator(self._spy, self._ema_fast, Resolution.DAILY)
        # self.register_indicator(self._spy, self._ema_fast, Resolution.DAILY)
        # self._ema_slow = ExponentialMovingAverage(200)
        # self.warm_up_indicator(self._spy, self._ema_slow, Resolution.DAILY)
        # self.register_indicator(self._spy, self._ema_slow, Resolution.DAILY)
        # self._trend = IndicatorExtensions.minus(self._ema_fast, self._ema_slow)
        self._adx = self.adx(self._spy, 14, Resolution.DAILY)
        # Alternatively, use a manual indicator.
        # self._adx = AverageDirectionalIndex(14)
        # self.warm_up_indicator(self._spy, self._adx, Resolution.DAILY)
        # self.register_indicator(self._spy, self._adx, Resolution.DAILY)
        self.plot_indicator("Trend", self._trend)
        self.plot_indicator("ADX", self._adx)
        self.schedule.on(
            self.date_rules.every_day(self._spy),
            self.time_rules.after_market_open(self._spy, 1),
            self._rebalance
        )

    def _rebalance(self) -> None:
        if not self._trend.is_ready or not self._adx.is_ready:
            return
        in_uptrend = self._trend.current.value > 0
        is_trending = self._adx.current.value > self._adx_threshold
        if in_uptrend and is_trending:
            if not self._spy.invested:
                self.set_holdings(self._spy, 1)
        elif self._spy.invested:
            self.liquidate()
