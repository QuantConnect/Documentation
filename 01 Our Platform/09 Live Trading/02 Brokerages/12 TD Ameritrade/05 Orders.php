<p>We model the TD Ameritrade API by supporting several order types, the <code>TimeInForce</code> order property, and order updates. When you deploy live algorithms, you can <a href='/docs/v2/our-platform/live-trading/algorithm-control#03-Place-Manual-Trades'>place manual orders</a> through the IDE.</p>

<h4>Order Types</h4>

<p>The following table describes the available order types for each asset class that TD Ameritrade supports:</p>

<table class="qc-table table" id='order-types-table'>
   <thead>
      <tr>
        <th style='width: 50%'>Order Type</th>
        <th style='width: 25%'>Equity</th>
        <th style='width: 25%'>Equity Options</th>
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
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-limit-orders'>StopLimitOrder</a></td>
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
StopMarketOrder(_symbol, quantity, stopPrice);
StopLimitOrder(_symbol, quantity, stopPrice, limitPrice);</pre>
    <pre class="python">self.MarketOrder(self.symbol, quantity)
self.LimitOrder(self.symbol, quantity, limit_price)
self.StopMarketOrder(self.symbol, quantity, stop_price)
self.StopLimitOrder(self.symbol, quantity, stop_price, limit_price)</pre>
</div>


<h4>Time In Force</h4>
<p>We model custom order properties from the TD Ameritrade API. TD Ameritrade supports the following <code>TimeInForce</code> instructions:</p>

<ul>
    <li><code>Day</code></li>
    <li><code>GoodTilCanceled</code></li>
    <li><code>GoodTilDate</code></li>
</ul>


<div class="section-example-container">
    <pre class="csharp">public override void Initialize()
{
    // Set the default order properties
    DefaultOrderProperties.TimeInForce = TimeInForce.GoodTilCanceled;
}

public override void OnData(Slice slice)
{
    // Use default order order properties
    LimitOrder(_symbol, quantity, limitPrice);
    
    // Override the default order properties
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new TDAmeritradeOrderProperties
               { 
                   TimeInForce = TimeInForce.Day
               });
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new TDAmeritradeOrderProperties
               { 
                   TimeInForce = TimeInForce.GoodTilDate(new DateTime(year, month, day))
               });
}</pre>
    <pre class="python">def Initialize(self) -&gt; None:
    # Set the default order properties
    self.DefaultOrderProperties.TimeInForce = TimeInForce.GoodTilCanceled

def OnData(self, slice: Slice) -&gt; None:
    # Use default order order properties
    self.LimitOrder(self.symbol, quantity, limit_price)
    
    # Override the default order properties
    order_properties = TDAmeritradeOrderProperties()
    order_properties.TimeInForce = TimeInForce.Day
    self.LimitOrder(self.symbol, quantity, limit_price, orderProperties=order_properties)

    order_properties.TimeInForce = TimeInForce.GoodTilDate(datetime(year, month, day))
    self.LimitOrder(self.symbol, quantity, limit_price, orderProperties=order_properties)</pre>
</div>


<h4>Updates</h4>
<p>TD Ameritrade supports order updates. You can define the following members of an <code>UpdateOrderFields</code> object to update active orders:</p>

<ul>
    <li><code>Quantity</code></li>
    <li><code>LimitPrice</code></li>
    <li><code>StopPrice</code></li>
    <li><code>Tag</code></li>
</ul>

<div class="section-example-container">
    <pre class="csharp">var ticket = StopLimitOrder(symbol, quantity, stopPrice, limitPrice, tag);
var orderFields = new UpdateOrderFields { 
    Quantity = newQuantity,
    LimitPrice = newLimitPrice,
    StopPrice = newStopPrice,
    Tag = newTag
};
ticket.Update(orderFields);</pre>
    <pre class="python">ticket = self.StopLimitOrder(self.symbol, quantity, stop_price, limit_price, tag)<br>update_fields = UpdateOrderFields()
update_fields.Quantity = new_quantity
update_fields.LimitPrice = new_limit_price
update_fields.StopPrice = new_stop_price
update_fields.Tag = new_tag
ticket.Update(update_fields)</pre>
</div>

<h4>Handling Splits</h4>
<p>In live trading, if you're using raw <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#11-Data-Normalization'>data normalization</a> and you have active limit, stop limit, or stop market orders in the market for a US Equity when a <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions#02-Splits'>stock split</a> occurs, the quantity, limit price, and stop price of your orders are automatically adjusted to reflect the stock split.</p>
