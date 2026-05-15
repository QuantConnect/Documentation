# region imports
from AlgorithmImports import *
# endregion

class VooWeeklyDcaAlgorithm(QCAlgorithm):
    _dollar_amount = 5000

    def initialize(self) -> None:
        self.set_start_date(2022, 1, 1)
        self.set_end_date(2024, 12, 31)
        self.set_cash(200000)

        self.settings.seed_initial_prices = True

        self._voo = self.add_equity("VOO", Resolution.MINUTE)

        self.schedule.on(
            self.date_rules.week_start(self._voo.symbol),
            self.time_rules.after_market_open(self._voo.symbol, 1),
            self._buy_voo
        )

    def on_warmup_finished(self) -> None:
        self._buy_voo()

    def on_order_event(self, order_event: OrderEvent) -> None:
        if order_event.status == OrderStatus.FILLED:
            self.plot("Fills", "VOO", order_event.fill_price * order_event.fill_quantity)

    def _buy_voo(self) -> None:
        if self.portfolio.cash < self._dollar_amount:
            return

        holdings_value = self._voo.holdings.holdings_value
        target_fraction = (holdings_value + self._dollar_amount) / self.portfolio.total_portfolio_value
        quantity = self.calculate_order_quantity(self._voo.symbol, target_fraction)

        if quantity > 0:
            self.market_order(self._voo.symbol, quantity)
