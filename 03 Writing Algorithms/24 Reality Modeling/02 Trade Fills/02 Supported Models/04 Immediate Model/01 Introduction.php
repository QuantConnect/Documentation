<p>The <code>ImmediateFillModel</code> is the default fill model if you trade non-Equity assets with the <a href="/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models/quantconnect-paper-trading">DefaultBrokerageModel</a>. This fill model fills trades completely and immediately. <br></p>

<div class="section-example-container">
<pre class="csharp">security.SetFillModel(new ImmediateFillModel());</pre>
<pre class="python">security.set_fill_model(ImmediateFillModel())</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/reality-modeling/trade-fills/immediate-fill-model-fill-logic.html"); ?>

<p class='csharp'>For more information about this model, see the <a target="_blank" rel="nofollow" href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Orders_1_1Fills_1_1ImmediateFillModel.html">class reference</a> and <a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Common/Orders/Fills/ImmediateFillModel.cs">implementation</a>.</p>
<p class='python'>For more information about this model, see the <a target="_blank" rel="nofollow" href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Orders/Fills/ImmediateFillModel/">class reference</a> and <a target="_blank" rel="nofollow" href="https://github.com/QuantConnect/Lean/blob/master/Common/Orders/Fills/ImmediateFillModel.cs">implementation</a>.</p>
