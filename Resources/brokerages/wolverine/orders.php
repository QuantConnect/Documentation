<h4>Order Types</h4>
<p><?= $cloudPlatform ? "Our Wolverine Execution Services integration" : "The <code>WolverineBrokerageModel</code>" ?> supports <a href='/docs/v2/writing-algorithms/trading-and-orders/order-types/market-orders'>market orders</a>.</p>

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
            <td><code>TimeInForce</code></td>
            <td>A <a href='/docs/v2/writing-algorithms/trading-and-orders/order-properties#03-Time-In-Force'>TimeInForce</a> instruction to apply to the order. The following instructions are supported:
                <ul>
                    <li><code>Day</code></li>
                    <li><code>GoodTilCanceled</code></li>
                </ul>
            </td>
        </tr>
        <tr>
            <td><code>Exchange</code></td>
            <td>Defines the exchange name for a particular market. For example, <code>Exchange.SMART</code></td>
        </tr>
        <tr>
            <td><code>ExchangePostFix</code></td>
            <td>The exchange post fix to apply if any. For example, if you set <code>Exchange</code> to <code>Exchange.SMART</code>, then "-INCA-TX" yields "SMART-INCA-TX"</td>
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
        ExchangePostFix = "-INCA-TX"
    };
}</pre>
    <pre class="python">def Initialize(self) -&gt; None:
    # Set the default order properties
    self.DefaultOrderProperties = WolverineOrderProperties()
    self.DefaultOrderProperties.TimeInForce = TimeInForce.GoodTilCanceled
    self.DefaultOrderProperties.Exchange = Exchange.SMART
    self.DefaultOrderProperties.ExchangePostFix = "-INCA-TX"</pre>
</div>
<? } ?>
