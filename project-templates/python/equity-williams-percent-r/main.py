# region imports
from AlgorithmImports import *
# endregion

class WilliamsPercentRIWMAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self.set_start_date(2022, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)
        self.settings.automatic_indicator_warm_up = True

        self._wmr_buy_threshold = -80
        self._wmr_sell_threshold = -20
        self._max_hold_days = 5

        self._symbol = self.add_equity("IWM", Resolution.MINUTE).symbol
        self._wmr = self.wilr(self._symbol, 14, Resolution.MINUTE)

        self._hold_days = 0

        self.schedule.on(
            self.date_rules.every_day(self._symbol),
            self.time_rules.before_market_close(self._symbol, 1),
            self._check_hold_duration
        )

    def on_data(self, data: Slice) -> None:
        if not self._wmr.is_ready:
            return

        wmr_value = self._wmr.current.value
        quantity = self.portfolio[self._symbol].quantity

        if self.time.minute % 10 == 0:
            self._plot(wmr_value)

        if quantity > 0 and wmr_value > self._wmr_sell_threshold:
            self.liquidate(self._symbol)
            self._hold_days = 0
            self._plot(wmr_value)
            return

        if quantity <= 0 and wmr_value < self._wmr_buy_threshold:
            self.set_holdings(self._symbol, 1.0)
            self._hold_days = 0
            self._plot(wmr_value)

    def _plot(self, wmr_value: float) -> None:
        self.plot("Williams %R", "IWM", wmr_value)
        self.plot("Williams %R", "-80", self._wmr_buy_threshold)
        self.plot("Williams %R", "-20", self._wmr_sell_threshold)

    def _check_hold_duration(self) -> None:
        if self.portfolio[self._symbol].quantity > 0:
            self._hold_days += 1
            if self._hold_days >= self._max_hold_days:
                self.liquidate(self._symbol)
                self._hold_days = 0
