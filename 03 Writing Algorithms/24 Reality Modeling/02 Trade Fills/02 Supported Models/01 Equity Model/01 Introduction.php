<p>The <code>EquityFillModel</code> is the default fill model if you trade Equity assets with the <a href="/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models/quantconnect-paper-trading">DefaultBrokerageModel</a>. This fill model fills trades completely and immediately. <br></p>

<div class="section-example-container">
<pre class="csharp">security.SetFillModel(new EquityFillModel());</pre>
<pre class="python">security.set_fill_model(EquityFillModel())</pre>
</div>

<p>The fill logic of each order depends on the order type, the data format of the security subscription, and the order direction. The following sections explain the fill logic of each order given these factors.</p>

<p>To view the implementation of this model, see the <a rel="nofollow" target="_blank" href="https://github.com/QuantConnect/Lean/blob/master/Common/Orders/Fills/EquityFillModel.cs">LEAN GitHub repository</a>.</p>
