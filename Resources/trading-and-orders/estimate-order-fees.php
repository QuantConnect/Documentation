<?
// Pages that already document the order factory or the fee model set these flags to skip the self-link.
$factoryText = isset($linkOrderFactory) && !$linkOrderFactory ? "order factory" : "<a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-factory'>order factory</a>";
$feeModelText = isset($linkFeeModel) && !$linkFeeModel ? "fee model" : "<a href='/docs/v2/writing-algorithms/reality-modeling/transaction-fees/key-concepts'>fee model</a>";
?>
<p>To estimate the modeled fee of an order before you place it, create an order request with the <?=$factoryText?> and convert it into an <code>Order</code> object with the <code class="csharp">QuantConnect.Orders.Order.CreateOrder</code><code class="python">Order.create_order</code> method. Then pass the order and its security to the <?=$feeModelText?> of the security. The conversion doesn't submit the order.</p>

<div class="section-example-container">
<pre class="csharp">var security = Securities[_symbol];
var request = OrderFactory.LimitOrder(_symbol, 100, limitPrice);
var order = QuantConnect.Orders.Order.CreateOrder(request);
var fee = security.FeeModel.GetOrderFee(new OrderFeeParameters(security, order)).Value;
Debug($"Estimated fee: {fee.Amount} {fee.Currency}");</pre>
<pre class="python">security = self.securities[self._symbol]
request = self.order_factory.limit_order(self._symbol, 100, limit_price)
order = Order.create_order(request)
fee = security.fee_model.get_order_fee(OrderFeeParameters(security, order)).value
self.debug(f"Estimated fee: {fee.amount} {fee.currency}")</pre>
</div>

<p>The estimate is a modeled fee. The fee model calculates it inside your algorithm with the current order and security values. In live trading, LEAN doesn't request the estimate from the brokerage. The fee that the brokerage charges can differ from the modeled fee, especially if the fee depends on the fill price or the fill quantity. To place the order after the estimate, pass the same request to the <code class="csharp">Order</code><code class="python">order</code> method.</p>
