<h4>Order Types</h4>

<p>The following table describes the available order types for each asset class that <?= $cloudPlatform ? "our <a rel='nofollow' target='_blank' href='https://qnt.co/ssc-eze'>SS&C Eze</a> integration" : "the <code>EzeBrokerageModel</code>" ?> supports:</p>

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
            <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-on-open-orders'>Market on Open</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-on-close-orders'>Market on Close</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td></td>
        <td></td>
        <td></td>
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

<h4>Order Properties</h4>
<p><?=$writingAlgorithms ? "The <code>EzeBrokerageModel</code> supports custom order properties." : "We model the SS&C Eze API." ?> The following table describes the members of the <code>EzeOrderProperties</code> object that you can set to customize order execution.</p>

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
        <tr>
            <td><code class="csharp">Route</code><code class="python">route</code></td>
            <td><code class='csharp'>string</code><code class='python'>str</code></td>
            <td>Sets the route name as shown in SS&C Eze EMS.</td>
            <td></td>
        </tr>
        <tr>
            <td><code class="csharp">Account</code><code class="python">account</code></td>
            <td><code class='csharp'>string</code><code class='python'>str</code></td>
            <td>Sets a semi-colon separated list of trade or neutral accounts the user has permission for, e.g., "TAL;TEST;USER1;TRADE" or "TAL;TEST;USER2;NEUTRAL".</td>
            <td></td>
        </tr>
        <tr>
            <td><code class="csharp">Notes</code><code class="python">notes</code></td>
            <td><code class='csharp'>string</code><code class='python'>str</code></td>
            <td>Sets the user message or notes.</td>
            <td></td>
        </tr>
    </tbody>
</table>

<? if ($writingAlgorithms) { ?>
<div class="section-example-container">
    <pre class="csharp">public override void Initialize()
{
    // Set the default order properties
    DefaultOrderProperties = new EzeOrderProperties
    {
        TimeInForce = TimeInForce.GoodTilCanceled,
        Notes = "Default order properties"
    };
}

public override void OnData(Slice slice)
{
    // Use default order order properties
    LimitOrder(_symbol, quantity, limitPrice);
    
    // Override the default order properties
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new EzeOrderProperties
               { 
                   TimeInForce = TimeInForce.Day,
                   Notes = "Default order properties"
               });
}</pre>
    <pre class="python">def initialize(self) -&gt; None:
    # Set the default order properties
    self.default_order_properties = EzeOrderProperties()
    self.default_order_properties.time_in_force = TimeInForce.GOOD_TIL_CANCELED
    self.default_order_properties.notes = "Default order properties"

def on_data(self, slice: Slice) -&gt; None:
    # Use default order order properties
    self.limit_order(self._symbol, quantity, limit_price)
    
    # Override the default order properties
    order_properties = EzeOrderProperties()
    order_properties.time_in_force = TimeInForce.DAY
    order_properties.notes = "Default order properties"
    self.limit_order(self._symbol, quantity, limit_price, order_properties=order_properties)</pre>
</div>
<? } ?>

<h4>Updates</h4>
<p><?= $writingAlgorithms ? "The <code>EzeBrokerageModel</code> supports" : "We model the SS&C Eze API by supporting" ?> <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#04-Update-Orders'>order updates</a>.</p>

<? include(DOCS_RESOURCES."/brokerages/handling-splits.html"); ?>