<p>We model the Binance and Binance US APIs by supporting several order types, supporting order properties, and not supporting order updates. When you deploy live algorithms, you can place manual orders through the IDE.</p>

<h4>Order Types</h4>
<p>Binance and Binance US support the following order types:</p>
<ul>
    <li><code>MarketOrder</code></li>
    <li><code>LimitOrder</code></li>
    <li><code>StopLimitOrder</code></li>
</ul>

<div class="section-example-container">
    <pre class="csharp">MarketOrder(_symbol, quantity);
LimitOrder(_symbol, quantity, limitPrice);
StopLimitOrder(_symbol, quantity, stopPrice, limitPrice);</pre>
    <pre class="python">self.MarketOrder(self.symbol, quantity)
self.LimitOrder(self.symbol, quantity, limit_price)
self.StopLimitOrder(self.symbol, quantity, stop_price, limit_price)</pre>
</div>

<h4>Order Properties<br></h4>
<p>We model custom order properties from the Binance and Binance US APIs. The following table describes the members of the <code>BinanceOrderProperties</code> object that you can set to customize order execution:</p>


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
         <td><code>PostOnly</code></td>
         <td>A flag to signal that the order must only add liquidity to the order book and not take liquidity from the order book. If part of the order results in taking liquidity rather than providing liquidity, the order is rejected without any part of it being filled.</td>
      </tr>
   </tbody>
</table>


<div class="section-example-container">
    <pre class="csharp">public override void Initialize()
{
    // Set the default order properties
    DefaultOrderProperties = new BinanceOrderProperties
    {
        TimeInForce = TimeInForce.GoodTilCanceled,
        PostOnly = false
    };
}

public override void OnData(Slice data)
{
    // Use default order order properties
    LimitOrder(_symbol, quantity, limitPrice);
    
    // Override the default order properties
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new BinanceOrderProperties
               { 
                   TimeInForce = TimeInForce.Day,
                   PostOnly = false
               });
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new BinanceOrderProperties
               { 
                   TimeInForce = TimeInForce.GoodTilDate(new DateTime(year, month, day)),
                   PostOnly = true
               });
}</pre>
    <pre class="python">def Initialize(self):
    # Set the default order properties
    self.DefaultOrderProperties = BinanceOrderProperties()
    self.DefaultOrderProperties.TimeInForce = TimeInForce.GoodTilCanceled
    self.DefaultOrderProperties.PostOnly = False

def OnData(self, data):
    # Use default order order properties
    self.LimitOrder(self.symbol, quantity, limit_price)
    
    # Override the default order properties
    order_properties = BinanceOrderProperties()
    order_properties.TimeInForce = TimeInForce.Day
    order_properties.PostOnly = True
    self.LimitOrder(self.symbol, quantity, limit_price, orderProperties=order_properties)

    order_properties.TimeInForce = TimeInForce.GoodTilDate(datetime(year, month, day))
    self.LimitOrder(self.symbol, quantity, limit_price, orderProperties=order_properties)</pre>
</div>

<h4>Updates</h4>
<p>We model the Binance and Binance US APIs by not supporting order updates, but you can cancel an existing order and then create a new order with the desired arguments.</p>

<div class="section-example-container">
    <pre class="csharp">var ticket = LimitOrder(_symbol, quantity, limitPrice);
ticket.Cancel();
ticket = LimitOrder(_symbol, newQuantity, newLimitPrice);</pre>
    <pre class="python">ticket = self.LimitOrder(self.symbol, quantity, limit_price)
ticket.Cancel()
ticket = self.LimitOrder(self.symbol, new_quantity, new_limit_price)</pre>
</div>

