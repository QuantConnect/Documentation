<p>We model the Bitfinex API by supporting several order types, order properties, and order updates. When you deploy live algorithms, you can place manual orders through the IDE.</p>

<h4>Order Types</h4>

<p>Bitfinex supports the following order types:</p>

<ul>
    <li><code>MarketOrder</code></li>
    <li><code>LimitOrder</code></li>
    <li><code>StopMarketOrder</code></li>
    <li><code>StopLimitOrder</code></li>
    <li><code>MarketOnOpenOrder</code></li>
    <li><code>MarketOnCloseOrder</code></li>
    <li><code>LimitIfTouchedOrder</code></li>
</ul>

<div class="section-example-container">
    <pre class="csharp">MarketOrder(_symbol, quantity);
LimitOrder(_symbol, quantity, limitPrice);
StopMarketOrder(_symbol, quantity, stopPrice);
StopLimitOrder(_symbol, quantity, stopPrice, limitPrice);
MarketOnOpenOrder(_symbol, quantity);
MarketOnCloseOrder(_symbol, quantity);
LimitIfTouchedOrder(_symbol, quantity, triggerPrice, limitPrice);</pre>
    <pre class="python">self.MarketOrder(self.symbol, quantity)
self.LimitOrder(self.symbol, quantity, limit_price)
self.StopMarketOrder(self.symbol, quantity, stop_price)
self.StopLimitOrder(self.symbol, quantity, stop_price, limit_price)
self.MarketOnOpenOrder(self.symbol, quantity)
self.MarketOnCloseOrder(self.symbol, quantity)
self.LimitIfTouchedOrder(self.symbol, quantity, trigger_price, limit_price)</pre>
</div>

<h4>Order Properties</h4>
<p>We model custom order properties from the Bitfinex API. The following table describes the members of the <code>BitfinexOrderProperties</code> object that you can set to customize order execution:</p>


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
         <td>
             A <code>TimeInForce</code> instruction to apply to the order. The following instructions are supported:
             <ul>
                 <li><code>Day</code></li>
                 <li><code>GoodTilCanceled</code></li>
                 <li><code>GoodTilDate</code></li>
             </ul>
         </td>
      </tr>
      <tr>
         <td><code>Hidden</code></td>
         <td>A flag to signal that the order should be hidden. Hidden orders do not appear in the order book, so they do not influence other market participants. Hidden orders incur the taker fee.</td>
      </tr>
      <tr>
         <td><code>PostOnly</code></td>
         <td>A flag to signal that the order must only add liquidity to the order book and not take liquidity from the order book. If part of the order results in taking liquidity rather than providing liquidity, the order is rejected without any part of it being filled.</td>
      </tr>
   </tbody>
</table>

<div class="section-example-container">
    <pre class="csharp">public override void Initialize()
{
    // Set the default order properties
    DefaultOrderProperties = new BitfinexOrderProperties
    {
        TimeInForce = TimeInForce.GoodTilCanceled,
        Hidden = false,
        PostOnly = false
    };
}

public override void OnData(Slice data)
{
    // Use default order order properties
    LimitOrder(_symbol, quantity, limitPrice);
    
    // Override the default order properties
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new BitfinexOrderProperties
               { 
                   TimeInForce = TimeInForce.Day,
                   Hidden = true,
                   PostOnly = false
               });
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new BitfinexOrderProperties
               { 
                   TimeInForce = TimeInForce.GoodTilDate(new DateTime(year, month, day)),
                   Hidden = false,
                   PostOnly = true
               });
}</pre>
    <pre class="python">def Initialize(self):
    # Set the default order properties
    self.DefaultOrderProperties = BitfinexOrderProperties()
    self.DefaultOrderProperties.TimeInForce = TimeInForce.GoodTilCanceled
    self.DefaultOrderProperties.Hidden = False
    self.DefaultOrderProperties.PostOnly = False

def OnData(self, data):
    # Use default order order properties
    self.LimitOrder(self.symbol, quantity, limit_price)
    
    # Override the default order properties
    order_properties = BitfinexOrderProperties()
    order_properties.TimeInForce = TimeInForce.Day
    order_properties.Hidden = True
    order_properties.PostOnly = False
    self.LimitOrder(self.symbol, quantity, limit_price, orderProperties=order_properties)

    order_properties.TimeInForce = TimeInForce.GoodTilDate(datetime(year, month, day))
    order_properties.Hidden = False
    order_properties.PostOnly = True
    self.LimitOrder(self.symbol, quantity, limit_price, orderProperties=order_properties)</pre>
</div>


<h4>Updates</h4>
<p>We model the Bitfinex API by supporting order updates. You can define the following members of the <code>UpdateOrderFields</code> object to update active orders:</p>

<ul>
    <li><code>Quantity</code></li>
    <li><code>LimitPrice</code></li>
    <li><code>StopPrice</code><code></code></li><li><code>TriggerPrice</code></li><li><code>Tag</code></li>
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



<h4>Place Manual Orders</h4>
<?php
include(DOCS_RESOURCES."/brokerages/place-manual-orders.php");
$getPlaceManualOrdersText("#16-View-Live-Performance");
?>