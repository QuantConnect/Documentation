
<h4>Order Types</h4>
<p>The following table describes the available order types for each asset class that <?= $cloudPlatform ? "our Binance and Binance US integrations" : "the Binance and Binance US brokerage models" ?> support:</p>

<table class="qc-table table" id='order-types-table'>
   <thead>
      <tr>
        <th style='width: 50%'>Order Type</th>
        <th>Crypto</th>
        <th>Crypto Futures</th>
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
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-limit-orders'>StopLimitOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td></td>
      </tr>
   </tbody>
</table>
<style>
#order-types-table td:not(:first-child), 
#order-types-table th:not(:first-child) {
    text-align: center;
}
</style>


<h4>Order Properties<br></h4>
<p><?= $writingAlgorithms? "The Binance and Binance US brokerage models supports custom order properties." : "We model custom order properties from the Binance and Binance US APIs."?> The following table describes the members of the <code>BinanceOrderProperties</code> object that you can set to customize order execution:</p>


<table class="table qc-table">
   <thead>
      <tr>
         <th style="width: 25%">Property</th>
         <th style="width: 75%">Description</th>
      </tr>
   </thead>
   <tbody>
      <tr>
         <td><code class="csharp">TimeInForce</code><code class="python">time_in_force</code></td>
         <td>
             A <a href='/docs/v2/writing-algorithms/trading-and-orders/order-properties#03-Time-In-Force'>TimeInForce</a> instruction to apply to the order. The following instructions are supported:
             <ul>
                 <li><code class="csharp">Day</code><code class="python">DAY</code></li>
                 <li><code class="csharp">GoodTilCanceled</code><code class="python">GOOD_TIL_CANCELED</code></li>
                 <li><code class="csharp">GoodTilDate</code><code class="python">GOOD_TIL_DATE</code></li>
             </ul>
         </td>
      </tr>
      <tr>
         <td><code class="csharp">PostOnly</code><code class="python">post_only</code></td>
         <td>A flag to signal that the order must only add liquidity to the order book and not take liquidity from the order book. If part of the order results in taking liquidity rather than providing liquidity, the order is rejected without any part of it being filled.</td>
      </tr>
   </tbody>
</table>

<?php if ($writingAlgorithms) { ?>
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

public override void OnData(Slice slice)
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
    <pre class="python">def initialize(self) -&gt; None:
    # Set the default order properties
    self.default_order_properties = BinanceOrderProperties()
    self.default_order_properties.time_in_force = TimeInForce.GOOD_TIL_CANCELED
    self.default_order_properties.post_only = False

def on_data(self, slice: Slice) -&gt; None:
    # Use default order order properties
    self.limit_order(self._symbol, quantity, limit_price)
    
    # Override the default order properties
    order_properties = BinanceOrderProperties()
    order_properties.time_in_force = TimeInForce.DAY
    order_properties.post_only = True
    self.limit_order(self._symbol, quantity, limit_price, order_properties=order_properties)

    order_properties.time_in_force = TimeInForce.good_til_date(datetime(year, month, day))
    self.limit_order(self._symbol, quantity, limit_price, order_properties=order_properties)</pre>
</div>
<?php } ?>

<h4>Updates</h4>
<p><?= $writingAlgorithms ? "The Binance and Binance US brokerage models don't support" : "We model the Binance and Binance US APIs by not supporting" ?> order updates, but you can cancel an existing order and then create a new order with the desired arguments. For more information about this workaround, see the <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#workaround-for-brokerages-that-dont-support-updates'>Workaround for Brokerages That Don’t Support Updates</a>.</p>
