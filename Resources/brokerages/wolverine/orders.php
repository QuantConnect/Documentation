<h4>Order Types</h4>
<p>The following table describes the available order types for each asset class that <?= $cloudPlatform ? "our Wolverine Execution Services integration" : "the <code>WolverineBrokerageModel</code>" ?> supports:</p>

<table class="qc-table table" id='order-types-table'>
   <thead>
      <tr>
        <th style='width: 50%'>Order Type</th>
        <th>Equity</th>
      </tr>
   </thead>
   <tbody>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-orders'>Market</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-on-close-orders'>Market on Close</a></td>
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


<h4>Updates</h4>
<p><?= $writingAlgorithms ? "The <code>WolverineBrokerageModel</code> doesn't support" : "We model the Wolverine Execution Services API by not supporting" ?> order updates.</p>

<h4>Extended Market Hours</h4>
<p><?= $cloudPlatform ? "Wolverine Execution Services" : "The <code>WolverineBrokerageModel</code>" ?> doesn't support extended market hours trading. If you place an order outside of regular trading hours, the order is invalid.</p>

<h4>Order Properties</h4>
<p><?= $writingAlgorithms ? "The <code>WolverineBrokerageModel</code> supports custom order properties." : "We model custom order properties from the Wolverine API." ?> The following table describes the members of the <code>WolverineOrderProperties</code> object that you can set to customize order execution:</p>

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
            <td><code>TimeInForce</code></td>
            <td>A <a href='/docs/v2/writing-algorithms/trading-and-orders/order-properties#03-Time-In-Force'>TimeInForce</a> instruction to apply to the order. The following instructions are supported:
                <ul>
                    <li><code class="csharp">Day</code><code class="python">DAY</code></li>
                    <li><code class="csharp">GoodTilCanceled</code><code class="python">GOOD_TIL_CANCELED</code></li>
                </ul>
            </td>
            <td><code class='csharp'>TimeInForce.GoodTilCanceled</code><code class='python'>TimeInForce.GOOD_TIL_CANCELED</code></td>
        </tr>
        <tr>
            <td><code class="csharp">Exchange</code><code class="python">exchange</code></td>
            <td><code>Exchange</code></td>
            <td>Defines the exchange name for a particular market. For example, <code>Exchange.SMART</code>.</td>
            <td></td>
        </tr>
        <tr>
            <td><code class="csharp">ExchangePostFix</code><code class="python">exchange_post_fix</code></td>
            <td><code class='csharp'>string</code><code class='python'>str</code></td>
            <td>The exchange post fix to apply if any. For example, if you set <code class="csharp">Exchange</code><code class="python">exchange</code> to <code>Exchange.SMART</code>, then "-INCA-TX" yields "SMART-INCA-TX".</td>
            <td></td>
        </tr>
        <tr>
            <td><code class="csharp">LocateBroker</code><code class="python">locate_broker</code></td>
            <td><code class='csharp'>string</code><code class='python'>str</code></td>
            <td>Identifies the broker source for borrowed stock.</td>
            <td></td>
        </tr>
        <tr>
            <td><code class="csharp">PositionSide</code><code class="python">position_side</code></td>
            <td><code class='csharp'>OrderPosition?</code><code class='python'>OrderPosition | NoneType</code></td>
            <td>Specify the position side in the order direction (buy-to-open, sell-to-close, etc.) instead of the default handling</td>
            <td><code class='csharp'>null</code><code class='python'>None</code></td>
        </tr>
    </tbody>
</table>

<? if ($writingAlgorithms) { ?>
<div class="section-example-container">
    <pre class="csharp">public override void Initialize()
{
    // Set the default order properties
    DefaultOrderProperties = new WolverineOrderProperties
    {
        TimeInForce = TimeInForce.GoodTilCanceled,
        Exchange = Exchange.SMART,
        ExchangePostFix = "-INCA-TX",
        OrderPosition = OrderPosition.BuyToOpen,
        // Optionally, you can specify for equity shorts which broker will provide the stock to short
        // if not specified defaults to broker will provide
        LocateBroker = "abn"
    };
}</pre>
    <pre class="python">def initialize(self) -&gt; None:
    # Set the default order properties
    self.default_order_properties = WolverineOrderProperties()
    self.default_order_properties.time_in_force = TimeInForce.GOOD_TIL_CANCELED
    self.default_order_properties.exchange = Exchange.SMART
    self.default_order_properties.exchange_post_fix = "-INCA-TX"
    self.default_order_properties.order_position = OrderPosition.BUY_TO_OPEN
    # Optionally, you can specify for equity shorts which broker will provide the stock to short
    # if not specified defaults to broker will provide
    self.default_order_properties.locate_broker = "abn"</pre>
</div>
<? } ?>

<p>If the <code class="csharp">PositionSide</code><code class="python">position_side</code> is not specified, the engine will determine it from the holdings and the order quantity. For example, if it's a sell short and will send <code>PositionSide</code><code class="csharp">SellShort</code><code class="python">SELL_SHORT</code>.</p>
