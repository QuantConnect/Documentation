# region imports
from AlgorithmImports import *
# endregion

class TelegramNotificationAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(100000)

        # automatic_indicator_warm_up only supports automatic indicators, not manual indicators.

        self.settings.automatic_indicator_warm_up = True

        self._spy = self.add_equity("SPY", Resolution.MINUTE).symbol

        self._ema50 = self.ema(self._spy, 50, Resolution.DAILY)
        # Alternatively, use a manual indicator.
        # self._ema50 = ExponentialMovingAverage(50)
        # self.warm_up_indicator(self._spy, self._ema50, Resolution.DAILY)
        # self.register_indicator(self._spy, self._ema50, Resolution.DAILY)
        self._ema200 = self.ema(self._spy, 200, Resolution.DAILY)
        # Alternatively, use a manual indicator.
        # self._ema200 = ExponentialMovingAverage(200)
        # self.warm_up_indicator(self._spy, self._ema200, Resolution.DAILY)
        # self.register_indicator(self._spy, self._ema200, Resolution.DAILY)

        self._previous_ema50_above = self._ema50.current.value > self._ema200.current.value

    def on_data(self, slice: Slice) -> None:
        if not self._ema50.is_ready or not self._ema200.is_ready:
            return

        ema50_above = self._ema50.current.value > self._ema200.current.value

        if ema50_above and not self._previous_ema50_above:
            self.set_holdings(self._spy, 1.0)
        elif not ema50_above and self._previous_ema50_above:
            self.set_holdings(self._spy, -1.0)

        self._previous_ema50_above = ema50_above

    def on_order_event(self, order_event: OrderEvent) -> None:
        if order_event.status != OrderStatus.FILLED:
            return

        if not self.live_mode:
            return

        direction = "BUY" if order_event.direction == OrderDirection.BUY else "SELL"
        message = f"SPY {direction} filled: {abs(order_event.fill_quantity)} @ {order_event.fill_price:.2f}"

        url = "https://api.telegram.org/bot<YOUR_BOT_TOKEN>/sendMessage"
        headers = {"Content-Type": "application/json"}
        body = f'{{"chat_id":"<YOUR_CHAT_ID>","text":"{message}"}}'

        self.notify.web(url, data=body, headers=headers)
        self.log(f"Notification sent: {message}")
