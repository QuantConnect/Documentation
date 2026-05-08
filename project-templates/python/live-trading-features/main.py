# region imports
from AlgorithmImports import *
# endregion


class LiveTradingFeaturesAlgorithm(QCAlgorithm):
    _connected = False

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 12)
        self.set_end_date(2024, 10, 1)
        self.set_cash(1_000_000)
        self.settings.seed_initial_prices = True
        self.settings.automatic_indicator_warm_up = True
        # Request SPY data to trade.
        self._spy = self.add_equity("SPY")
        # Create an EMA indicator to generate trade signals.
        self._spy.ema = self.ema(self._spy, 20, Resolution.DAILY)

    def _notify_all(self, subject: str, message: str) -> None:
        self.notify.email("email@address.com", subject, message)
        message = f"{self.time:yyyyMMdd}: {subject} > {message}"
        self.log(message)
        # See https://www.quantconnect.com/docs/v2/writing-algorithms/live-trading/notifications
        # For all notification methods.
        self.notify.sms("+16191234567", message)

    def on_brokerage_disconnect(self) -> None:
        self._notify_all(f"Brokerage disconnected on {self.time}", "-")
        self._connected = False

    def on_brokerage_reconnect(self) -> None:
        self._notify_all(f"Brokerage reconnected on {self.time}", "-")
        self._connected = True

    def on_brokerage_message(self, message_event: BrokerageMessageEvent) -> None:
        match message_event.type:
            case BrokerageMessageType.ERROR:
                self._notify_all(f"Brokerage Message", str(message_event))
            case _:
                self.log(str(message_event))

    def on_data(self, data: Slice) -> None:
        self._notify_ema_cross(data)

    def _notify_ema_cross(self, slice: Slice) -> None:
        if not self.live_mode:
            return
        bar = slice.bars.get(self._spy)
        if not bar:
            return
        # Trend-following strategy using price and EMA.
        # If the price is above EMA, SPY is in an uptrend, and we buy it.
        # We sent a link to our email address and await confirmation.
        if bar.close > self._spy.ema.current.value and not self._spy.holdings.is_long:
            link = self.link({"ticker": "SPY", "size": 1})
            self._notify_all("Trade Confirmation Needed", f"Click here to run: {link}")
        elif bar.close < self._spy.ema.current.value and not self._spy.holdings.is_short:
            link = self.link({"ticker": "SPY", "size": -1})
            self._notify_all("Trade Confirmation Needed", f"Click here to run: {link}")

    def on_command(self, data: DynamicData) -> Optional[bool]:
        if not self._connected:
            self._notify_all(f"OnCommand :: brokerage disconnected", f"Cannot place order for {data}")
            return False
        # The algorithm will place the order if we click the email link to confirm the trade.
        self.set_holdings(data["ticker"], data["size"])
        return True
