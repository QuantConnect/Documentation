<h4>Order Types</h4>

<p>The following table describes the available order types for each asset class that <?= $cloudPlatform ? "our Webull integration" : "the <code>WebullBrokerageModel</code>" ?> supports:</p>

<table class="qc-table table" id='order-types-table'>
   <thead>
      <tr>
        <th style='width: 40%'>Order Type</th>
        <th style='width: 20%'>Equity</th>
        <th style='width: 20%'>Equity Options</th>
        <th style='width: 20%'>Index Options</th>
      </tr>
   </thead>
   <tbody>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-orders'>Market</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/limit-orders'>Limit</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-market-orders'>Stop market</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-limit-orders'>Stop limit</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/trailing-stop-orders'>Trailing stop</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
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

<p><?=$writingAlgorithms ? "The <code>WebullBrokerageModel</code> supports custom order properties." : "We model custom order properties from the Webull API." ?> The following table describes the members of the <code>WebullOrderProperties</code> object that you can set to customize order execution.</p>

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
                </ul>
                Market orders support only <code class="csharp">Day</code><code class="python">DAY</code>, which the brokerage sets automatically. Option and Index Option sell orders also support only <code class="csharp">Day</code><code class="python">DAY</code>.
            </td>
            <td><code class='csharp'>TimeInForce.GoodTilCanceled</code><code class='python'>TimeInForce.GOOD_TIL_CANCELED</code></td>
        </tr>
        <tr>
            <td><code class="csharp">OutsideRegularTradingHours</code><code class="python">outside_regular_trading_hours</code></td>
            <td><code>bool</code></td>
            <td>If set to true, allows orders to also trigger or fill outside of regular trading hours. This property applies to Equity orders only and isn't supported for market orders.</td>
            <td><code class="csharp">false</code><code class="python">False</code></td>
        </tr>
    </tbody>
</table>

<? if ($writingAlgorithms) { ?>
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
               orderProperties: new WebullOrderProperties
               {
                   TimeInForce = TimeInForce.Day,
                   OutsideRegularTradingHours = true
               });
}</pre>
    <pre class="python">def initialize(self) -&gt; None:
    # Set the default order properties
    self.default_order_properties.time_in_force = TimeInForce.GOOD_TIL_CANCELED

def on_data(self, slice: Slice) -&gt; None:
    # Use default order order properties
    self.limit_order(self._symbol, quantity, limit_price)

    # Override the default order properties
    order_properties = WebullOrderProperties()
    order_properties.time_in_force = TimeInForce.DAY
    order_properties.outside_regular_trading_hours = True
    self.limit_order(self._symbol, quantity, limit_price, order_properties=order_properties)</pre>
</div>
<? } ?>

<h4>Updates</h4>
<p><?= $writingAlgorithms ? "The <code>WebullBrokerageModel</code> supports" : "We model the Webull API by supporting" ?> <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#04-Update-Orders'>order updates</a>.</p>

<? include(DOCS_RESOURCES."/brokerages/handling-splits.html"); ?>
