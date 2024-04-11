<p>The <code>FutureFillModel</code> is the default fill model if you trade Futures contracts with the <a href="/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models/quantconnect-paper-trading">DefaultBrokerageModel</a>. This fill model fills trades completely and immediately. <br></p>

<div class="section-example-container">
<pre class="csharp">security.SetFillModel(new FutureFillModel());</pre>
<pre class="python">security.set_fill_model(FutureFillModel())</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/reality-modeling/trade-fills/immediate-fill-model-fill-logic.html"); ?>

<p>To view the implementation of this model, see the <a rel="nofollow" target="_blank" href="https://github.com/QuantConnect/Lean/blob/master/Common/Orders/Fills/FutureFillModel.cs">LEAN GitHub repository</a>.</p>
