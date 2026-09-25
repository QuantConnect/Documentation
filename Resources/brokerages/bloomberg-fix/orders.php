<h4>Order Types</h4>
<p>The following table describes the available order types for each asset class that <?= $writingAlgorithms ? "the <code>BloombergFixBrokerageModel</code>" : "our Bloomberg&trade; FIX integration" ?> supports:</p>

<table class="qc-table table" id='order-types-table'>
   <thead>
      <tr>
        <th>Order Type</th>
        <th>Equity</th>
        <th>Equity Options</th>
        <th>Index Options</th>
        <th>Futures</th>
      </tr>
   </thead>
   <tbody>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-orders'>Market</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-on-open-orders'>Market on open</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td></td>
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-on-close-orders'>Market on close</a></td>
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
      </tr>
      <tr>
        <td><a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-market-orders'>Stop market</a></td>
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
      </tr>
   </tbody>
</table>

<style>
#order-types-table td:not(:first-child),
#order-types-table th:not(:first-child) {
    text-align: center;
}
</style>

<p>Market on open orders are unavailable for Futures. If you place one, the order is invalid.</p>

<h4>Updates</h4>
<p><?= $writingAlgorithms ? "The <code>BloombergFixBrokerageModel</code> supports" : "We model the Bloomberg&trade; FIX connection by supporting" ?> <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#04-Update-Orders'>order updates</a>. The Bloomberg&trade; FixNet HUB accepts cancel/replace requests (FIX tag 35=G), so you can update the quantity, limit price, stop price, order type, and time in force of an open order.</p>

<h4>Order Properties</h4>
<p><?= $writingAlgorithms ? "The <code>BloombergFixBrokerageModel</code> supports custom order properties." : "We model custom order properties from the Bloomberg&trade; FIX connection." ?> The <code>BloombergFixOrderProperties</code> class inherits the <code>FixOrderProperties</code> class that every <a href='/docs/v2/cloud-platform/live-trading/brokerages/fix-connections'>FIX connection</a> shares. The following table describes the members of the <code>BloombergFixOrderProperties</code> object that you can set to customize order execution:</p>

<table class="table qc-table" id="bloomberg-fix-order-properties-table">
    <thead>
        <tr>
         <th style="width: 18%">Property</th>
         <th style="width: 17%">Data Type</th>
         <th style="width: 45%">Description</th>
         <th style="width: 20%">Default Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code class="csharp">TimeInForce</code><code class="python">time_in_force</code></td>
            <td><code>TimeInForce</code></td>
            <td>A <a href='/docs/v2/writing-algorithms/trading-and-orders/order-properties#03-Time-In-Force'>TimeInForce</a> instruction to apply to the order.</td>
            <td><code class='csharp'>TimeInForce.GoodTilCanceled</code><code class='python'>TimeInForce.GOOD_TIL_CANCELED</code></td>
        </tr>
        <tr>
            <td><code class="csharp">HandleInstruction</code><code class="python">handle_instruction</code></td>
            <td><code class='csharp'>char?</code><code class='python'>str/NoneType</code></td>
            <td>The instruction for order handling on the broker floor. The following values are supported:
                <ul>
                    <li><code class="csharp">FixOrderProperties.AutomatedExecutionOrderPrivate</code><code class="python">FixOrderProperties.AUTOMATED_EXECUTION_ORDER_PRIVATE</code>: Automated execution order, private, no broker intervention</li>
                    <li><code class="csharp">FixOrderProperties.AutomatedExecutionOrderPublic</code><code class="python">FixOrderProperties.AUTOMATED_EXECUTION_ORDER_PUBLIC</code>: Automated execution order, public, broker intervention OK</li>
                    <li><code class="csharp">FixOrderProperties.ManualOrder</code><code class="python">FixOrderProperties.MANUAL_ORDER</code>: Staged order, broker intervention required</li>
                </ul>
            </td>
            <td></td>
        </tr>
        <tr>
            <td><code class="csharp">Notes</code><code class="python">notes</code></td>
            <td><code class='csharp'>string</code><code class='python'>str</code></td>
            <td>The free form text instructions that may be sent to the broker.</td>
            <td></td>
        </tr>
        <tr>
            <td><code class="csharp">LocateBroker</code><code class="python">locate_broker</code></td>
            <td><code class='csharp'>string</code><code class='python'>str</code></td>
            <td>The broker that the shares are borrowed from for a short sale. Reads and writes the <code>LocateBroker</code> FIX tag 5700.</td>
            <td></td>
        </tr>
        <tr>
            <td><code class="csharp">LocateReqd</code><code class="python">locate_reqd</code></td>
            <td><code class='csharp'>string</code><code class='python'>str</code></td>
            <td>Whether a locate is required for the short sale, <code>"Y"</code> or <code>"N"</code>. Reads and writes the <code>LocateReqd</code> FIX tag 114.</td>
            <td></td>
        </tr>
        <tr>
            <td><code class="csharp">AutomaticPositionSides</code><code class="python">automatic_position_sides</code></td>
            <td><code class='csharp'>bool</code><code class='python'>bool</code></td>
            <td>A flag that determines whether to set the position side of the order (buy-to-open, sell-to-close, etc.) from your current holdings. If you disable it, the order is a plain buy or sell without FIX tag 77.</td>
            <td><code class='csharp'>true</code><code class='python'>True</code></td>
        </tr>
        <tr>
            <td><code class="csharp">PositionSide</code><code class="python">position_side</code></td>
            <td><code class='csharp'>OrderPosition?</code><code class='python'>OrderPosition/NoneType</code></td>
            <td>
               An <code>OrderPosition</code> object that specifies the position side of the order (buy-to-open, sell-to-close, etc.) instead of your current holdings.
               This member has precedence over <code class="csharp">AutomaticPositionSides</code><code class="python">automatic_position_sides</code>.
            </td>
            <td></td>
        </tr>
        <tr>
            <td><code class="csharp">AdditionalProperties</code><code class="python">additional_properties</code></td>
            <td><code class='csharp'>BaseExtendedDictionary&lt;string, string&gt;</code><code class='python'>BaseExtendedDictionary[str, str]</code></td>
            <td>The custom FIX tags to send with the order. The key is the FIX tag number and the value is the tag value.</td>
            <td>An empty dictionary</td>
        </tr>
    </tbody>
</table>

<style>
#bloomberg-fix-order-properties-table {
    table-layout: fixed;
}

#bloomberg-fix-order-properties-table td,
#bloomberg-fix-order-properties-table code {
    white-space: normal;
    overflow-wrap: break-word;
}
</style>

<? if ($writingAlgorithms) { ?>
<div class="section-example-container">
    <pre class="csharp">public override void Initialize()
{
    // Set the default order properties to borrow the shares of short sales from a specific broker
    DefaultOrderProperties = new BloombergFixOrderProperties
    {
        TimeInForce = TimeInForce.GoodTilCanceled,
        LocateBroker = "BMTB",
        LocateReqd = "Y"
    };
}</pre>
    <pre class="python">def initialize(self) -&gt; None:
    # Set the default order properties to borrow the shares of short sales from a specific broker
    self.default_order_properties = BloombergFixOrderProperties()
    self.default_order_properties.time_in_force = TimeInForce.GOOD_TIL_CANCELED
    self.default_order_properties.locate_broker = "BMTB"
    self.default_order_properties.locate_reqd = "Y"</pre>
</div>
<? } ?>

<p>The position side of an order sets the FIX Side tag 54 and the OpenClose tag 77. The following table shows the tag values for each position side:</p>

<table class="table qc-table">
    <thead>
        <tr>
         <th>Position Side</th>
         <th>Tag 54</th>
         <th>Tag 77</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code class="csharp">OrderPosition.BuyToOpen</code><code class="python">OrderPosition.BUY_TO_OPEN</code></td>
            <td><code>1</code> (buy)</td>
            <td><code>O</code> (open)</td>
        </tr>
        <tr>
            <td><code class="csharp">OrderPosition.BuyToClose</code><code class="python">OrderPosition.BUY_TO_CLOSE</code></td>
            <td><code>1</code> (buy)</td>
            <td><code>C</code> (close)</td>
        </tr>
        <tr>
            <td><code class="csharp">OrderPosition.SellToOpen</code><code class="python">OrderPosition.SELL_TO_OPEN</code></td>
            <td><code>5</code> (sell short)</td>
            <td><code>O</code> (open)</td>
        </tr>
        <tr>
            <td><code class="csharp">OrderPosition.SellToClose</code><code class="python">OrderPosition.SELL_TO_CLOSE</code></td>
            <td><code>2</code> (sell)</td>
            <td><code>C</code> (close)</td>
        </tr>
    </tbody>
</table>

<p>Tag 77 separates a buy that covers a short position from a buy that opens a long position, since both orders carry 54=1. Order updates and cancellations send the same tag values as the original order. To set the position side yourself instead of using your current holdings, set the <code class="csharp">PositionSide</code><code class="python">position_side</code> property:</p>

<? if ($writingAlgorithms) { ?>
<div class="section-example-container">
    <pre class="csharp">// Mark the buy as a buy to cover (tag 77 = C)
var orderProperties = new BloombergFixOrderProperties { PositionSide = OrderPosition.BuyToClose };
MarketOrder("SPY", 100, orderProperties: orderProperties);</pre>
    <pre class="python"># Mark the buy as a buy to cover (tag 77 = C)
order_properties = BloombergFixOrderProperties()
order_properties.position_side = OrderPosition.BUY_TO_CLOSE
self.market_order("SPY", 100, order_properties=order_properties)</pre>
</div>
<? } ?>

<p>Your FIX counterparty may require tags that the preceding properties don't cover. To send them with your orders, add them to the <code class="csharp">AdditionalProperties</code><code class="python">additional_properties</code> dictionary. For example, the following code marks the orders as direct market access (DMA) with tag 9301:</p>

<? if ($writingAlgorithms) { ?>
<div class="section-example-container">
    <pre class="csharp">public override void Initialize()
{
    // Set the default order properties to mark orders as direct market access (DMA)
    var orderProperties = new BloombergFixOrderProperties();
    orderProperties.AdditionalProperties["9301"] = "1";
    DefaultOrderProperties = orderProperties;
}</pre>
    <pre class="python">def initialize(self) -&gt; None:
    # Set the default order properties to mark orders as direct market access (DMA)
    order_properties = BloombergFixOrderProperties()
    order_properties.additional_properties["9301"] = "1"
    self.default_order_properties = order_properties</pre>
</div>
<? } ?>

<p class="python">The dictionary starts empty and you can't replace it with a plain Python dictionary. To add several tags at once, call the <code>update</code> method with a dictionary. To remove all the tags, call the <code>clear</code> method.</p>

<? include(DOCS_RESOURCES."/brokerages/handling-splits.html"); ?>
