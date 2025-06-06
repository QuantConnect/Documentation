<h4>Order Types</h4>

<p>The following table describes the available order types for each asset class that <?= $cloudPlatform ? "our <a rel='nofollow' target='_blank' href='https://qnt.co/tastytrade'>Tastytrade</a> integration" : "the <code>TastytradeBrokerageModel</code>" ?> supports:</p>

<table class="qc-table table" id='order-types-table'>
   <thead>
      <tr>
        <th style='width: 20%'>Order Type</th>
        <th style='width: 16%'>Equity</th>
        <th style='width: 16%'>Equity Options</th>
        <th style='width: 16%'>Futures</th>
        <th style='width: 16%'>Future Options</th>
        <th style='width: 16%'>Index Options</th>
      </tr>
   </thead>
   <tbody>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-orders'>Market</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/limit-orders'>Limit</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-market-orders'>Stop market</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-limit-orders'>Stop limit</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
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

<h4>Order Properties</h4>
<p><?=$writingAlgorithms ? "The <code>TastytradeBrokerageModel</code> supports custom order properties." : "We model the Tastytrade API." ?> The following table describes the members of the <code>TastytradeOrderProperties</code> object that you can set to customize order execution.</p>

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
            <td>A <a href='/docs/v2/writing-algorithms/trading-and-orders/order-properties#03-Time-In-Force'>TimeInForce</a> instruction to apply to the order. The following instructions are supported:
                <ul>
                    <li><code class="csharp">Day</code><code class="python">DAY</code></li>
                    <li><code class="csharp">GoodTilCanceled</code><code class="python">GOOD_TIL_CANCELED</code></li>
                    <li><code class="csharp">GoodTilDate</code><code class="python">good_til_date</code></li>
                </ul>
            </td>
            <td><code class='csharp'>TimeInForce.GoodTilCanceled</code><code class='python'>TimeInForce.GOOD_TIL_CANCELED</code></td>
        </tr>
    </tbody>
</table>


<? if ($writingAlgorithms) { ?>
<div class="section-example-container">
    <pre class="csharp">public override void Initialize()
{
    // Set the default order properties
    DefaultOrderProperties = new TastytradeOrderProperties
    {
        TimeInForce = TimeInForce.GoodTilCanceled
    };
}

public override void OnData(Slice slice)
{
    // Use default order order properties
    LimitOrder(_symbol, quantity, limitPrice);
    
    // Override the default order properties
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new TastytradeOrderProperties
               { 
                   TimeInForce = TimeInForce.Day
               });
}</pre>
    <pre class="python">def initialize(self) -&gt; None:
    # Set the default order properties
    self.default_order_properties = TastytradeOrderProperties()
    self.default_order_properties.time_in_force = TimeInForce.GOOD_TIL_CANCELED

def on_data(self, slice: Slice) -&gt; None:
    # Use default order order properties
    self.limit_order(self._symbol, quantity, limit_price)
    
    # Override the default order properties
    order_properties = TastytradeOrderProperties()
    order_properties.time_in_force = TimeInForce.DAY
    self.limit_order(self._symbol, quantity, limit_price, order_properties=order_properties)</pre>
</div>
<? } ?>

<h4>Updates</h4>
<p><?= $writingAlgorithms ? "The <code>TastytradeBrokerageModel</code> supports" : "We model the Tastytrade API by supporting" ?> <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#04-Update-Orders'>order updates</a>.</p>

<? include(DOCS_RESOURCES."/brokerages/handling-splits.html"); ?>