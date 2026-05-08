# region imports
from AlgorithmImports import *
# endregion


class FinancialAdvisorAlgorithmAlgorithm(QCAlgorithm):
    _connected = False

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 12)
        self.set_end_date(2024, 10, 1)
        self.set_cash(1_000_000)
        self.settings.seed_initial_prices = True
        # Financial Advisor is a live trading feature of the Interactive Brokers integration.
        self.set_brokerage_model(BrokerageName.INTERACTIVE_BROKERS_BROKERAGE, AccountType.MARGIN)
        # Set the default order properties.
        self.default_order_properties = InteractiveBrokersOrderProperties()
        self.default_order_properties.fa_group = "TestGroupEQ"
        self.default_order_properties.fa_method = "Equal"
        self.default_order_properties.account = "DU123456"
        # Request SPY data to trade.
        self.add_equity("SPY")

    def on_data(self, data: Slice) -> None:
        if not self.portfolio.invested:
            self.set_holdings("SPY", 1)

    # region Live trading features
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
    # endregion
