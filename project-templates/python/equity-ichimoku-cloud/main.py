# region imports
from AlgorithmImports import *
# endregion

class EquityIchimokuCloudAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        self._qqq = self.add_equity("QQQ")
        # Add the IchimokuKinkoHyo indicator.
        self._ichimoku = self.ichimoku(self._qqq, 9, 26, 52, 26, 26, 52, Resolution.DAILY)
        # Alternatively, use a manual indicator.
        # self._ichimoku = IchimokuKinkoHyo(9, 26, 52, 26, 26, 52)
        # self.warm_up_indicator(self._qqq, self._ichimoku, Resolution.DAILY)
        # self.register_indicator(self._qqq, self._ichimoku, Resolution.DAILY)
        # Manual warm-up: need warm_up_period + 1 so both .current and .previous are valid.
        self.indicator_history(self._ichimoku, self._qqq, self._ichimoku.warm_up_period + 1, Resolution.DAILY)
        # Plot all five Ichimoku components — Tenkan, Kijun, Senkou A, Senkou B, Chikou.
        self.plot_indicator(
            "Ichimoku",
            self._ichimoku.tenkan,
            self._ichimoku.kijun,
            self._ichimoku.senkou_a,
            self._ichimoku.senkou_b,
            self._ichimoku.chikou,
        )
        # Add a Scheduled Event to scan for trades every trading day at 8 AM.
        self.schedule.on(self.date_rules.every_day(self._qqq), self.time_rules.at(8, 0), self._rebalance)

    def _rebalance(self) -> None:
        if not self._ichimoku.is_ready:
            return
        tenkan = self._ichimoku.tenkan.current.value
        kijun = self._ichimoku.kijun.current.value
        senkou_a = self._ichimoku.senkou_a.current.value
        senkou_b = self._ichimoku.senkou_b.current.value
        previous_tenkan_above = self._ichimoku.tenkan.previous.value > self._ichimoku.kijun.previous.value
        current_tenkan_above = tenkan > kijun
        crossed_above = current_tenkan_above and not previous_tenkan_above
        crossed_below = not current_tenkan_above and previous_tenkan_above
        # Cloud top = max(Senkou A, Senkou B); only go long when price sits above the cloud.
        cloud_top = max(senkou_a, senkou_b)
        if not self.portfolio.invested and crossed_above and self._qqq.price > cloud_top:
            self.set_holdings(self._qqq, 1)
        elif self.portfolio.invested and crossed_below:
            self.liquidate()
