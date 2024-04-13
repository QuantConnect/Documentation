<p>Terminal Link enables you to create and manage Bloomberg™ orders.</p>

<h4>Order Types</h4>
<p>The following table describes the available order types for each asset class that Terminal Link supports:</p>

<table class="qc-table table" id="order-types-table">
   <thead>
      <tr>
        <th>Order Type</th>
        <th>Equity</th>
        <th>Equity Options</th>
        <th>Futures</th>
        <th>Index Options</th>
      </tr>
   </thead>
   <tbody>
      <tr>
        <td><a href="/docs/v2/writing-algorithms/trading-and-orders/order-types/market-orders">MarketOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href="/docs/v2/writing-algorithms/trading-and-orders/order-types/limit-orders">LimitOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href="/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-market-orders">StopMarketOrder</a></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
        <td><img src="https://cdn.quantconnect.com/i/tu/check.png" alt="green check" width="15px;"></td>
      </tr>
      <tr>
        <td><a href="/docs/v2/writing-algorithms/trading-and-orders/order-types/stop-limit-orders">StopLimitOrder</a></td>
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
<p>We model custom order properties from the Bloomberg EMSX API. The following table describes the members of the <code>TerminalLinkOrderProperties</code> object that you can set to customize order execution:</p>

<table class="table qc-table" class='order-properties-table'>
    <thead>
        <tr>
            <th style="width: 25%">Property</th>
            <th style="width: 75%">Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>TimeInForce</code></td>
            <td>A <a href='/docs/v2/writing-algorithms/trading-and-orders/order-properties#03-Time-In-Force'>TimeInForce</a> instruction to apply to the order. The following instructions are supported:
                <ul>
                    <li><code>Day</code></li>
                    <li><code>GoodTilCanceled</code></li>
                    <li><code>GoodTilDate</code></li>
                </ul>
            </td>
        </tr>
        <tr>
            <td><code>Notes</code></td>
            <td>The free form instructions that may be sent to the broker.</td>
        </tr>
        <tr>
            <td><code>HandlingInstruction</code></td>
            <td>The instructions for handling the order or route. The values can be preconfigured or a value customized by the broker.</td>
        </tr>
        <tr>
            <td><code>CustomNotes1</code></td>
            <td>Custom user order notes 1. For more information about custom order notes, see <a rel='nofollow' target='_blank' href='https://emsx-api-doc.readthedocs.io/en/latest/programmable/description.html?highlight=Notes#custom-notes-free-text-fields'>Custom Notes & Free Text Fields</a> in the EMSX API documentation</td>
        </tr>
        <tr>
            <td><code>CustomNotes2</code></td>
            <td>Custom user order notes 2.</td>
        </tr>
        <tr>
            <td><code>CustomNotes3</code></td>
            <td>Custom user order notes 3.</td>
        </tr>
        <tr>
            <td><code>CustomNotes4</code></td>
            <td>Custom user order notes 4.</td>
        </tr>
        <tr>
            <td><code>CustomNotes5</code></td>
            <td>Custom user order notes 5.</td>
        </tr>
        <tr>
            <td><code>Account</code></td>
            <td>The EMSX account.</td>
        </tr>
        <tr>
            <td><code>Broker</code></td>
            <td>The EMSX broker code.</td>
        </tr>
        <tr>
            <td><code>Strategy</code></td>
            <td>
               A <code>StrategyParameters</code> object that represents the EMSX order strategy details. You must append strategy parameters in the order that the EMSX API expects. 
               The following strategy names are supported: "DMA", "DESK", "VWAP", "TWAP", "FLOAT", "HIDDEN", "VOLUMEINLINE", "CUSTOM", "TAP", "CUSTOM2", "WORKSTRIKE", "TAPNOW", "TIMED", "LIMITTICK", "STRIKE"
            </td>
        </tr>
    </tbody>
</table>

<? if ($writingAlgorithms) { ?>
<div class="section-example-container">
    <pre class="csharp">public override void Initialize()
{
    // Set the default order properties
    DefaultOrderProperties = new TerminalLinkOrderProperties
    {
        TimeInForce = TimeInForce.GoodTilCanceled,
        Strategy = new TerminalLinkOrderProperties.StrategyParameters(
            "VWAP",
            new List&lt;TerminalLinkOrderProperties.StrategyField&gt;
            {
                new("09:30:00"),
                new("10:30:00"),
                new(),
                new()
            }
         )
    };
}

public override void OnData(Slice slice)
{
    // Use default order order properties
    LimitOrder(_symbol, quantity, limitPrice);
    
    // Override the default order properties
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new TerminalLinkOrderProperties
               { 
                   TimeInForce = TimeInForce.Day,
                   Account = "account1"
               });
    LimitOrder(_symbol, quantity, limitPrice, 
               orderProperties: new TerminalLinkOrderProperties
               { 
                   TimeInForce = TimeInForce.GoodTilDate(new DateTime(year, month, day)),
                   Account = "account2"
               });
}</pre>
    <pre class="python">def initialize(self) -&gt; None:
    # Set the default order properties
    self.default_order_properties = TerminalLinkOrderProperties()
    self.default_order_properties.time_in_force = TimeInForce.GOOD_TIL_CANCELED
    self.default_order_properties.strategy = TerminalLinkOrderProperties.strategy_parameters(
        "VWAP",
        [
            TerminalLinkOrderProperties.strategy_field("09:30:00"),
            TerminalLinkOrderProperties.strategy_field("10:30:00"),
            TerminalLinkOrderProperties.strategy_field(),
            TerminalLinkOrderProperties.strategy_field()
        ]
    )

def on_data(self, slice: Slice) -&gt; None:
    # Use default order order properties
    self.limit_order(self.symbol, quantity, limit_price)
    
    # Override the default order properties
    order_properties = TerminalLinkOrderProperties()
    order_properties.time_in_force = TimeInForce.DAY
    order_properties.account = "account1"
    self.limit_order(self.symbol, quantity, limit_price, order_properties=order_properties)

    order_properties.time_in_force = TimeInForce.good_til_date(datetime(year, month, day))
    order_properties.account = "account2"
    self.limit_order(self.symbol, quantity, limit_price, order_properties=order_properties)</pre>
</div>
<? } ?>

<p>For more information about the format that the Bloomberg EMSX API expects, see <a href='https://emsx-api-doc.readthedocs.io/en/latest/programmable/requestResponse.html#create-order-and-route-extended-request' rel='nofollow' target='_blank'>Create Order and Route Extended Request</a> in the EMSX API documentation and the <a rel='nofollow' target='_blank' href='https://www.mathworks.com/help/datafeed/emsx.createorderandroutewithstrat.html'>createOrderAndRouteWithStrat documentation</a> on the MathWorks website.</p>

<h4>Get Open Orders</h4>
<p>Terminal Link lets you <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/transaction-manager'>access open orders</a>.</p>

<h4>Monitor Fills</h4>
<p>Terminal Link allows you to monitor orders as they fill through <a href='/docs/v2/writing-algorithms/trading-and-orders/order-events'>order events</a>.</p>

<h4>Updates</h4>
<p>Terminal Link doesn't support <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#04-Update-Orders'>order updates</a>.</p>

<h4>Cancellations</h4>
<p>Terminal Link enables you to <a href='/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets#05-Cancel-Orders'>cancel open orders</a>.</p>

<? include(DOCS_RESOURCES."/brokerages/handling-splits.html"); ?>
