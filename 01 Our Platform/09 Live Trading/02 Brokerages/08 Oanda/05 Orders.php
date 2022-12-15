<p>We model the Oanda API by supporting several order types, a <code>TimeInForce</code> order instruction, and order updates. When you deploy live algorithms, you can <a href='/docs/v2/our-platform/live-trading/algorithm-control#03-Place-Manual-Trades'>place manual orders</a> through the IDE.</p>

<h4>Order Types</h4>
<p>The following table describes the available order types for each asset class that Oanda supports:</p>

<table class="qc-table table" id='order-types-table'>
   <thead>
      <tr>
        <th style='width: 50%'>Order Type</th>
        <th style='width: 25%'>Forex</th>
        <th style='width: 25%'>CFD</th>
      </tr>
   </thead>
   <tbody>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-orders'>MarketOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/limit-orders'>LimitOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-market-orders'>StopMarketOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
   </tbody>
</table>

<style>
#order-types-table td:not(:first-child), 
#order-types-table th:not(:first-child) {
    text-align: center;
}
</style>

<div class="section-example-container">
    <pre class="csharp">MarketOrder(_symbol, quantity);
LimitOrder(_symbol, quantity, limitPrice);
StopMarketOrder(_symbol, quantity, stopPrice);</pre>
    <pre class="python">self.MarketOrder(self.symbol, quantity)
self.LimitOrder(self.symbol, quantity, limit_price)
self.StopMarketOrder(self.symbol, quantity, stop_price)</pre>
</div>

<h4>Time In Force</h4>
<p>We model the <code>GoodTilCanceled</code> <code>TimeInForce</code> from the Oanda API.<br></p>
<div class="section-example-container">
    <pre class="csharp">DefaultOrderProperties.TimeInForce = TimeInForce.GoodTilCanceled;
LimitOrder(_symbol, quantity, limitPrice);</pre>
    <pre class="python">self.DefaultOrderProperties.TimeInForce = TimeInForce.GoodTilCanceled
self.LimitOrder(self.symbol, quantity, limit_price)</pre>
</div>

<h4>Updates</h4>
<p>We model the Oanda API by supporting order updates. You can define the following members of an <code>UpdateOrderFields</code> object to update active orders:</p>

<ul>
    <li><code>Quantity</code></li>
    <li><code>LimitPrice</code></li>
    <li><code>StopPrice</code><code></code></li><li><code>Tag</code></li>
</ul>

<div class="section-example-container">
    <pre class="csharp">var ticket = LimitOrder(symbol, quantity, limitPrice, tag);
var orderFields = new UpdateOrderFields { <br>    Quantity = newQuantity,<br>    LimitPrice = newLimitPrice,<br>    Tag = newTag<br>};
ticket.Update(orderFields);</pre>
    <pre class="python">ticket = self.LimitOrder(symbol, quantity, limit_price, tag)<br>update_fields = UpdateOrderFields()
update_fields.Quantity = new_quantity
update_fields.LimitPrice = new_limit_price
update_fields.Tag = new_tag
ticket.Update(update_fields)</pre>
</div>
