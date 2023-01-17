<p>We model the IB API by supporting several order types, order properties, and order updates. When you deploy live algorithms, you can <a href='/docs/v2/cloud-platform/live-trading/algorithm-control#03-Place-Manual-Trades'>place manual orders</a> through the IDE.</p>

<h4>Order Types</h4>
<p>The following table describes the order types that IB supports. For specific details about each order type, refer to the IB documentation.<br></p>


<table class="qc-table table">
  <thead>
    <tr>
      <th style="width: 50%">Order Type</th>
      <th style="width: 50%">IB Documentation Page</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>MarketOrder</code></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/index.php?f=602">Market Orders</a></td>
    </tr>
    <tr>
      <td><code>LimitOrder</code></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/index.php?f=593">Limit Orders</a></td>
    </tr>
    <tr>
      <td><code>LimitIfTouchedOrder</code></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/index.php?f=592">Limit if Touched Orders</a></td>
    </tr>
    <tr>
      <td><code>StopMarketOrder</code></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/index.php?f=609">Stop Orders</a></td>
    </tr>
    <tr>
      <td><code>StopLimitOrder</code></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/index.php?f=608">Stop-Limit Orders</a></td>
    </tr>
    <tr>
      <td><code>MarketOnOpenOrder</code></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/index.php?f=598">Market-on-Open (MOO) Orders</a></td>
    </tr>
    <tr>
      <td><code>MarketOnCloseOrder</code></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/index.php?f=599">Market-on-Close (MOC) Orders</a></td>
    </tr>
    <tr>
      <td><code>ComboMarketOrder</code></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/trading/orders/spread.php">Spread Orders</a></td>
    </tr>
    <tr>
      <td><code>ComboLimitOrder</code></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/trading/orders/spread.php">Spread Orders</a></td>
    </tr>
    <tr>
      <td><code>ComboLegLimitOrder</code></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.com/en/trading/orders/spread.php">Spread Orders</a></td>
    </tr>
    <tr>
      <td><code>ExerciseOption</code></td>
      <td><a rel="nofollow" target="_blank" href="https://www.interactivebrokers.ca/en/trading/exerciseCloseout.php">Options Exercise</a></td>
    </tr>
  </tbody>
</table>

<div class="section-example-container">
    <pre class="csharp">MarketOrder(_symbol, quantity);
LimitOrder(_symbol, quantity, limitPrice);
LimitIfTouchedOrder(_symbol, quantity, triggerPrice, limitPrice);
StopMarketOrder(_symbol, quantity, stopPrice);
StopLimitOrder(_symbol, quantity, stopPrice, limitPrice);
MarketOnOpenOrder(_symbol, quantity);
MarketOnCloseOrder(_symbol, quantity);
ExerciseOption(_optionSymbol, quantity);</pre>
    <pre class="python">self.MarketOrder(self.symbol, quantity)
self.LimitOrder(self.symbol, quantity, limit_price)
self.LimitIfTouchedOrder(self.symbol, quantity, trigger_price, limit_price)
self.StopMarketOrder(self.symbol, quantity, stop_price)
self.StopLimitOrder(self.symbol, quantity, stop_price, limit_price)
self.MarketOnOpenOrder(self.symbol, quantity)
self.MarketOnCloseOrder(self.symbol, quantity)
self.ExerciseOption(self.option_symbol, quantity)</pre>
</div>

<p>Market on open orders aren't available for Futures or Future Options.</p>

<p>Option exercise orders aren't available for Index Options or cash-settled US Equity Options.</p>

<p>Combo orders are only available for Options.</p>

<h4>Order Properties</h4>

<p>We model custom order properties from the IB API. The following table describes the members of the <code>InteractiveBrokersOrderProperties</code> object that you can set to customize order execution. The table does not include the preceding methods for FA accounts.</p>

<table class="table qc-table">
    <thead>
        <tr>
            <th style="width: 25%">Property</th>
            <th style="width: 75%">Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>TimeInForce</code></td>
            <td>A <code>TimeInForce</code> instruction to apply to the order. The following instructions are supported:
                <ul>
                    <li><code>Day</code></li>
                    <li><code>GoodTilCanceled</code></li>
                    <li><code>GoodTilDate</code></li>
                </ul>
            </td>
        </tr>
        <tr>
            <td><code>OutsideRegularTradingHours</code></td>
            <td>A flag to signal that the order may be triggered and filled outside of regular trading hours.</td>
        </tr>
    </tbody>
</table>


<div class="section-example-container">
    <pre class="csharp">public override void Initialize()
{
    // Set the default order properties
    DefaultOrderProperties = new InteractiveBrokersOrderProperties
    {
        TimeInForce = TimeInForce.GoodTilCanceled,
        OutsideRegularTradingHours = false
    };
}

public override void OnData(Slice slice)
{
    // Use default order order properties
    LimitOrder(_symbol, quantity, limitPrice);
    
    // Override the default order properties
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new InteractiveBrokersOrderProperties
               { 
                   TimeInForce = TimeInForce.Day,
                   OutsideRegularTradingHours = false
               });
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new InteractiveBrokersOrderProperties
               { 
                   TimeInForce = TimeInForce.GoodTilDate(new DateTime(year, month, day)),
                   OutsideRegularTradingHours = true
               });
}</pre>
    <pre class="python">def Initialize(self) -&gt; None:
    # Set the default order properties
    self.DefaultOrderProperties = InteractiveBrokersOrderProperties()
    self.DefaultOrderProperties.TimeInForce = TimeInForce.GoodTilCanceled
    self.DefaultOrderProperties.OutsideRegularTradingHours = False

def OnData(self, slice: Slice) -&gt; None:
    # Use default order order properties
    self.LimitOrder(self.symbol, quantity, limit_price)
    
    # Override the default order properties
    order_properties = InteractiveBrokersOrderProperties()
    order_properties.TimeInForce = TimeInForce.Day
    order_properties.OutsideRegularTradingHours = True
    self.LimitOrder(self.symbol, quantity, limit_price, orderProperties=order_properties)

    order_properties.TimeInForce = TimeInForce.GoodTilDate(datetime(year, month, day))
    self.LimitOrder(self.symbol, quantity, limit_price, orderProperties=order_properties)</pre>
</div>

<h4>Updates</h4>
<p>IB supports order updates. You can define the following members of an <code>UpdateOrderFields</code> object to update active orders:</p>

<ul>
    <li><code>Quantity</code></li>
    <li><code>LimitPrice</code></li>
    <li><code>StopPrice</code></li>
    <li><code>TriggerPrice</code></li>
    <li><code>Tag</code></li>
</ul>

<div class="section-example-container">
    <pre class="csharp">var ticket = StopLimitOrder(symbol, quantity, stopPrice, limitPrice, tag);
var orderFields = new UpdateOrderFields { <br>    Quantity = newQuantity,<br>    LimitPrice = newLimitPrice,<br>    StopPrice = newStopPrice,<br>    Tag = newTag<br>};
ticket.Update(orderFields);</pre>
    <pre class="python">ticket = self.StopLimitOrder(symbol, quantity, stop_price, limit_price, tag)<br>update_fields = UpdateOrderFields()
update_fields.Quantity = new_quantity<br>update_fields.LimitPrice = new_limit_price<br>update_fields.StopPrice = new_stop_price
update_fields.Tag = new_tag
ticket.Update(update_fields)</pre>
</div>

<h4>Fill Time</h4>
<p>IB has a 400 millisecond fill time for live orders.</p>

<h4>Financial Advisor Group Orders</h4>
<p>To place FA group orders, see <a href='/docs/v2/cloud-platform/live-trading/brokerages/interactive-brokers#03-Financial-Advisors'>Financial Advisors</a>.</p>

<h4>Handling Splits</h4>
<p>In live trading, if you're using raw <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#11-Data-Normalization'>data normalization</a> and you have active limit, stop limit, or stop market orders in the market for a US Equity when a <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-actions#02-Splits'>stock split</a> occurs, the quantity, limit price, and stop price of your orders are automatically adjusted to reflect the stock split.</p>

<h4>Order Size Limits</h4>

<p>The following table shows the maximum number of units you can buy of each currency when the currency is the <a href='/docs/v2/writing-algorithms/securities/key-concepts#03-Quote-Currency'>base currency</a> in a Forex pair:</p>

<table class="table qc-table" id="order-size-limits-table">
    <thead>
        <tr>
            <th>Base Currency</th>
            <th>Description</th>
            <th>Maximum Order Size (Millions)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>USD</td>
            <td>US Dollar</td>
            <td>7</td>
        </tr>
        <tr>
            <td>AUD</td>
            <td>Australian Dollar</td>
            <td>6</td>
        </tr>
        <tr>
            <td>CAD</td>
            <td>Canadian Dollar</td>
            <td>6</td>
        </tr>
        <tr>
            <td>CHF</td>
            <td>Swiss Franc</td>
            <td>6</td>
        </tr>
        <tr>
            <td>CNH</td>
            <td>China Renminbi (offshore)</td>
            <td>40</td>
        </tr>
        <tr>
            <td>CZK</td>
            <td>Czech Koruna</td>
            <td>0</td>
        </tr>
        <tr>
            <td>DKK</td>
            <td>Danish Krone</td>
            <td>35</td>
        </tr>
        <tr>
            <td>EUR</td>
            <td>Euro</td>
            <td>5</td>
        </tr>
        <tr>
            <td>GBP</td>
            <td>British Pound Sterling</td>
            <td>4</td>
        </tr>
        <tr>
            <td>HKD</td>
            <td>Hong Kong Dollar</td>
            <td>50</td>
        </tr>
        <tr>
            <td>HUF</td>
            <td>Hungarian Forint</td>
            <td>0</td>
        </tr>
        <tr>
            <td>HKD</td>
            <td>Israeli Shekel</td>
            <td>0</td>
        </tr>
        <tr>
            <td>KRW</td>
            <td>Korean Won</td>
            <td>750</td>
        </tr>
        <tr>
            <td>JPY</td>
            <td>Japanese Yen</td>
            <td>550</td>
        </tr>
        <tr>
            <td>MXN</td>
            <td>Mexican Peso</td>
            <td>70</td>
        </tr>
        <tr>
            <td>NOK</td>
            <td>Norwegian Krone</td>
            <td>35</td>
        </tr>
        <tr>
            <td>NZD</td>
            <td>New Zealand Dollar</td>
            <td>8</td>
        </tr>
        <tr>
            <td>RUB</td>
            <td>Russian Ruble</td>
            <td>30</td>
        </tr>
        <tr>
            <td>SEK</td>
            <td>Swedish Krona</td>
            <td>40</td>
        </tr>
        <tr>
            <td>SGD</td>
            <td>Singapore Dollar</td>
            <td>8</td>
        </tr>
    </tbody>
</table>

<style>
#order-size-limits-table td:last-child, 
#order-size-limits-table th:last-child {
    text-align: right;
}
</style>

