# region imports
from AlgorithmImports import *
# endregion


class OneCancelOtherExampleAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.settings.automatic_indicator_warm_up = True
        self._spy = self.add_equity("SPY")
        self._spy.ema = self.ema(self._spy, 14, Resolution.DAILY)
        self._spy.has_oco = False

    def on_data(self, data: Slice) -> None:
        if self._spy.has_oco:
            return
        bar = data.bars.get(self._spy)
        if not bar:
            return
        # If the price is above the EMA, we will buy 75% of the portfolio value.
        # And place the OCO orders to sell it.
        # Otherwise, we will short 75% of the portfolio value.
        # And place OCO orders to rebuy.
        ema = self._spy.ema.current.value
        price = bar.close
        weight =  0.75 if ema > price else -.75
        stop_price = bar.close * (.95 if ema > price else 1.05)
        limit_price = bar.close * (1.05 if ema > price else .95)
        quantity = self.calculate_order_quantity(self._spy, weight)
        self.market_order(self._spy, quantity)
        self._spy.stop_loss = self.stop_market_order(self._spy, -quantity, stop_price)
        self._spy.take_profit = self.limit_order(self._spy, -quantity, limit_price)
        self._spy.has_oco = True

    def on_order_event(self, order_event: OrderEvent) -> None:
        if order_event.status == OrderStatus.FILLED:
            equity = self.securities[order_event.symbol]
            match order_event.ticket.order_type:
                case OrderType.STOP_MARKET:
                    equity.take_profit.cancel()
                    equity.has_oco = False
                case OrderType.LIMIT:
                    equity.stop_loss.cancel()
                    equity.has_oco = False
