# region imports
from AlgorithmImports import *
# endregion

class FisherTransformAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self.set_start_date(2022, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)

        # automatic_indicator_warm_up only supports automatic indicators, not manual indicators.
        self.settings.automatic_indicator_warm_up = True

        self._qqq = self.add_equity("QQQ", Resolution.MINUTE)

        self._fisher = self.fish(self._qqq.symbol, 10, Resolution.DAILY)
        # Alternatively, use a manual indicator.
        # self._fisher = FisherTransform(10)
        # self.warm_up_indicator(self._qqq, self._fisher, Resolution.DAILY)
        # self.register_indicator(self._qqq, self._fisher, Resolution.DAILY)
        self.plot_indicator("Fisher", self._fisher)

        self.schedule.on(
            self.date_rules.every_day(self._qqq.symbol),
            self.time_rules.after_market_open(self._qqq.symbol, 30),
            self._rebalance
        )

    def _rebalance(self) -> None:
        if not self._fisher.is_ready:
            return

        current = self._fisher.current.value
        previous = self._fisher.previous.value
        holdings = self._qqq.holdings.quantity

        # Exit on cross of 0
        if holdings > 0 and previous < 0 < current:
            self.liquidate(self._qqq.symbol)
        elif holdings < 0 and previous > 0 > current:
            self.liquidate(self._qqq.symbol)

        # Entry only if flat (one position at a time)
        if holdings == 0:
            if current > 2.0 and current < previous:
                self.set_holdings(self._qqq.symbol, -1.0)
            elif current < -2.0 and current > previous:
                self.set_holdings(self._qqq.symbol, 1.0)
