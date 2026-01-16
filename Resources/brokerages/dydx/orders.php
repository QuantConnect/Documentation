<h4>Order Types</h4>
<p>The following table describes the available order types for each asset class that <?= $cloudPlatform ? "our dYdX integration" : "the <code>dYdXBrokerageModel</code>" ?> supports:</p>

<table class="qc-table table" id='order-types-table'>
   <thead>
      <tr>
        <th style='width: 50%'>Order Type</th>
        <th>Crypto Future</th>
      </tr>
   </thead>
   <tbody>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-orders'>Market</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/limit-orders'>Limit</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-market-orders'>Stop market</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-limit-orders'>Stop limit</a></td>
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

<h4>Order Properties</h4>
<p><?= $writingAlgorithms ? "The <code>dYdXBrokerageModel</code> supports custom order properties." : "We model custom order properties from the dYdX API." ?> The following table describes the members of the <code>dYdXOrderProperties</code> object that you can set to customize order execution:</p>


<table class="table qc-table">
   <thead>
      <tr>
         <th>Property</th>
         <th>Data Type</th>
         <th>Description</th>
         <th>Default Value</th>
      </tr>
   </thead>
   <tbody>
      <tr>
         <td><code class="csharp">TimeInForce</code><code class="python">time_in_force</code></td>
         <td><code>TimeInForce</code></td>
         <td>
             A <a href='/docs/v2/writing-algorithms/trading-and-orders/order-properties#03-Time-In-Force'>TimeInForce</a> instruction to apply to the order. The following instructions are supported:
             <ul>
                 <li><code class="csharp">Day</code><code class="python">DAY</code></li>
                 <li><code class="csharp">GoodTilCanceled</code><code class="python">GOOD_TIL_CANCELED</code><sup>1</sup></li>
                 <li><code class="csharp">GoodTilDate</code><code class="python">good_til_date</code></li>
             </ul>
         </td>
         <td><code class='csharp'>TimeInForce.GoodTilCanceled</code><code class='python'>TimeInForce.GOOD_TIL_CANCELED</code></td>
      </tr>
      <tr>
         <td><code class="csharp">GasLimit</code><code class="python">gas_limit</code></td>
         <td><code class="csharp">ulong</code><code class="python">int</code></td>
         <td>The maximum amount of gas to use for the order.</td>
         <td>1_000_000</td>
      </tr>
      <tr>
         <td><code class="csharp">GoodTilBlockOffset</code><code class="python">good_til_block_offset</code></td>
         <td><code class="csharp">ulong</code><code class="python">int</code></td>
         <td>The block height at which the order expires.</td>
         <td>20</td>
      </tr>
      <tr>
         <td><code>IOC</code><sup>2</sup></td>
         <td><code>bool</code></td>
         <td>Enforces that an order only be placed on the book as a maker order. Note this means that validators will cancel any newly placed post only orders that would cross with other maker.</td>
         <td><code class="csharp">false</code><code class="python">False</code></td>
      </tr>
      <tr>
         <td><code class="csharp">PostOnly</code><code class="python">post_only</code><sup>2</sup></td>
         <td><code>bool</code></td>
         <td>A flag to signal that the order must only add liquidity to the order book and not take liquidity from the order book. If part of the order results in taking liquidity rather than providing liquidity, the order is rejected without any part of it being filled. This order property is only available for limit orders.</td>
         <td><code class="csharp">false</code><code class="python">False</code></td>
      </tr>
      <tr>
         <td><code class="csharp">ReduceOnly</code><code class="python">reduce_only</code></td>
         <td><code>bool</code></td>
         <td>A flag to signal that the order must only reduce your current position size. For more information about this order property, see <a href='https://help.dydx.trade/en/articles/166978-reduce-only-order' rel='nofollow' target='_blank'>Reduce-Only Order</a> on the dYdX website.</td>
         <td><code class="csharp">false</code><code class="python">False</code></td>
      </tr>
   </tbody>
</table>
<p><sup>1</sup> <code class="csharp">GoodTilCanceled</code><code class="python">GOOD_TIL_CANCELED</code> is not fully supported. These orders expire in 90 days.</p>
<p><sup>2</sup> You cannot set <code class="csharp">PostOnly</code><code class="python">post_only</code> when <code class="python">IOC</code> is already set and vice-versa. Only one execution type can be active at a time.</p>

<?php if ($writingAlgorithms) { ?>
<div class="section-example-container">
    <pre class="csharp">public override void Initialize()
{
    // Set the default order properties
    DefaultOrderProperties = new dYdXOrderProperties
    {
        TimeInForce = TimeInForce.GoodTilCanceled,
        ReduceOnly = false,
        PostOnly = false
    };
}

public override void OnData(Slice slice)
{
    // Use default order order properties
    LimitOrder(_symbol, quantity, limitPrice);
    
    // Override the default order properties
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new dYdXOrderProperties
               { 
                   TimeInForce = TimeInForce.Day,
                   ReduceOnly = true,
                   PostOnly = false
               });
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new dYdXOrderProperties
               { 
                   TimeInForce = TimeInForce.GoodTilDate(new DateTime(year, month, day)),
                   ReduceOnly = false,
                   PostOnly = true
               });
}</pre>
    <pre class="python">def initialize(self) -&gt; None:
    # Set the default order properties
    self.default_order_properties = dYdXOrderProperties()
    self.default_order_properties.time_in_force = TimeInForce.GOOD_TIL_CANCELED
    self.default_order_properties.reduce_only = False
    self.default_order_properties.post_only = False

def on_data(self, slice: Slice) -&gt; None:
    # Use default order order properties
    self.limit_order(self._symbol, quantity, limit_price)
    
    # Override the default order properties
    order_properties = dYdXOrderProperties()
    order_properties.time_in_force = TimeInForce.DAY
    order_properties.reduce_only = True
    order_properties.post_only = False
    self.limit_order(self._symbol, quantity, limit_price, order_properties=order_properties)

    order_properties.time_in_force = TimeInForce.good_til_date(datetime(year, month, day))
    order_properties.reduce_only = False
    order_properties.post_only = True
    self.limit_order(self._symbol, quantity, limit_price, order_properties=order_properties)</pre>
</div>
<?php } ?>

<h4>Updates</h4>
<p><?= $writingAlgorithms ? "The <code>dYdXBrokerageModel</code> doesn't support order updates" : "We model the dYdX API by not supporting order updates" ?>, but you can cancel an existing order and then create a new order with the desired arguments. For more information about this workaround, see the <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#workaround-for-brokerages-that-dont-support-updates'>Workaround for Brokerages That Don't Support Updates</a>.</p>