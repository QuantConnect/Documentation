# region imports
from AlgorithmImports import *
# endregion


class CustomModelslgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 12)
        self.set_end_date(2024, 10, 1)
        self.set_cash(1_000_000)
        self.settings.seed_initial_prices = True
        # The brokerage model sets the reality models that reflect the brokerage behavior.
        self.set_brokerage_model(BrokerageName.INTERACTIVE_BROKERS_BROKERAGE, AccountType.MARGIN)
        def custom_security_initalizer(security: Security) -> None:
            security.set_fee_model(CustomFeeModel(self))
            security.set_fill_model(CustomFillModel(self))
            security.set_slippage_model(CustomSlippageModel(self))
            # Apply NullBuyingPowerModel for Options to allow unconstrained position sizing.
            if security.Type in [SecurityType.OPTION, SecurityType.INDEX_OPTION]:
                security.set_buying_power_model(BuyingPowerModel.NULL)
            else:
                security.set_buying_power_model(CustomBuyingPowerModel(self))
        # Override some of the models.
        self.add_security_initializer(custom_security_initalizer)
        # Request SPY data to trade after we set the model and security initializer.
        self.add_equity("SPY")

    def on_data(self, data: Slice) -> None:
        if not self.portfolio.invested:
            self.set_holdings("SPY", 1)


# Inherit from ImmediateFillModel to reuse base logic and override only market_fill.
class CustomFillModel(ImmediateFillModel):

    def __init__(self, algorithm: QCAlgorithm) -> None:
        super().__init__()
        self._algorithm = algorithm
        self._absolute_remaining_by_order_id: dict[int, float] = {}
        self._random = Random(387510346)

    def market_fill(self, asset: Security, order: MarketOrder) -> OrderEvent:
        absolute_remaining = order.absolute_quantity
        if order.id in self._absolute_remaining_by_order_id.keys():
            absolute_remaining = self._absolute_remaining_by_order_id[order.id]
        fill = super().market_fill(asset, order)
        absolute_fill_quantity = int(min(absolute_remaining, self._random.next(0, 2*int(order.absolute_quantity))))
        fill.fill_quantity = np.sign(order.quantity) * absolute_fill_quantity
        if absolute_remaining == absolute_fill_quantity:
            fill.status = OrderStatus.FILLED
            if self._absolute_remaining_by_order_id.get(order.id):
                self._absolute_remaining_by_order_id.pop(order.id)
        else:
            absolute_remaining = absolute_remaining - absolute_fill_quantity
            self._absolute_remaining_by_order_id[order.id] = absolute_remaining
            fill.status = OrderStatus.PARTIALLY_FILLED
        self._algorithm.log(f"CustomFillModel: {fill}")
        return fill


class CustomFeeModel(FeeModel):

    def __init__(self, algorithm: QCAlgorithm) -> None:
        super().__init__()
        self._algorithm = algorithm

    def get_order_fee(self, parameters: OrderFeeParameters) -> OrderFee:
        # Custom fee math.
        fee = max(1, parameters.security.price
                  * parameters.order.absolute_quantity
                  * 0.00001)
        self._algorithm.log(f"CustomFeeModel: {fee}")
        return OrderFee(CashAmount(fee, "USD"))


class CustomSlippageModel:

    def __init__(self, algorithm: QCAlgorithm) -> None:
        self._algorithm = algorithm

    def get_slippage_approximation(self, asset: Security, order: Order) -> float:
        # Custom slippage math.
        slippage = asset.price * 0.0001 * float(np.log10(2 * order.absolute_quantity))
        self._algorithm.log(f"CustomSlippageModel: {slippage}")
        return slippage


class CustomBuyingPowerModel(BuyingPowerModel):

    def __init__(self, algorithm: QCAlgorithm) -> None:
        super().__init__()
        self._algorithm = algorithm

    def has_sufficient_buying_power_for_order(self, parameters: HasSufficientBuyingPowerForOrderParameters) -> HasSufficientBuyingPowerForOrderResult:
        # Custom behavior: this model will assume that there is always enough buying power.
        has_sufficient_buying_power_for_order_result = HasSufficientBuyingPowerForOrderResult(True)
        self._algorithm.log(f"CustomBuyingPowerModel: {has_sufficient_buying_power_for_order_result.is_sufficient}")
        return has_sufficient_buying_power_for_order_result


# Simplified fill model implementing Market, Stop Market, and Limit fills.
class SimpleCustomFillModel(FillModel):

    def __init__(self) -> None:
        super().__init__()

    def _create_order_event(self, asset: Security, order: Order) -> OrderEvent:
        utc_time = Extensions.convert_to_utc(asset.local_time, asset.exchange.time_zone)
        return OrderEvent(order, utc_time, OrderFee.ZERO)

    def _set_order_event_to_filled(self, fill: OrderEvent, fill_price: float, fill_quantity: float) -> OrderEvent:
        fill.status = OrderStatus.FILLED
        fill.fill_quantity = fill_quantity
        fill.fill_price = fill_price
        return fill

    def _get_trade_bar(self, asset: Security, order_direction: OrderDirection) -> TradeBar:
        trade_bar = asset.cache.get_data(TradeBar)
        if trade_bar: return trade_bar
        # Tick-resolution data doesn't have TradeBar, use the asset price.
        price = asset.price
        return TradeBar(asset.local_time, asset.symbol, price, price, price, price, 0)

    def market_fill(self, asset: Security, order: Order) -> OrderEvent:
        fill = self._create_order_event(asset, order)
        if order.status == OrderStatus.CANCELED: return fill
        return self._set_order_event_to_filled(
            fill,
            asset.cache.ask_price if order.direction == OrderDirection.BUY else asset.cache.bid_price,
            order.quantity
        )

    def stop_market_fill(self, asset: Security, order: Order) -> OrderEvent:
        fill = self._create_order_event(asset, order)
        if order.status == OrderStatus.CANCELED: return fill
        stop_price = order.stop_price
        trade_bar = self._get_trade_bar(asset, order.direction)
        if order.direction == OrderDirection.SELL and trade_bar.low < stop_price:
            return self._set_order_event_to_filled(fill, stop_price, order.quantity)
        if order.direction == OrderDirection.BUY and trade_bar.high > stop_price:
            return self._set_order_event_to_filled(fill, stop_price, order.quantity)
        return fill

    def limit_fill(self, asset: Security, order: Order) -> OrderEvent:
        fill = self._create_order_event(asset, order)
        if order.status == OrderStatus.CANCELED: return fill
        limit_price = order.limit_price
        trade_bar = self._get_trade_bar(asset, order.direction)
        if order.direction == OrderDirection.SELL and trade_bar.high > limit_price:
            return self._set_order_event_to_filled(fill, limit_price, order.quantity)
        if order.direction == OrderDirection.BUY and trade_bar.low < limit_price:
            return self._set_order_event_to_filled(fill, limit_price, order.quantity)
        return fill
