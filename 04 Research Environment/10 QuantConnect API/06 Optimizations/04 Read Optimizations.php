<p>To get the results of an optimization, call the <code>ReadOptimization</code> method with the optimization Id.</p>

<div class="section-example-container">
    <pre class="csharp">var optimization = api.ReadOptimization(optimizationId);</pre>
    <pre class="python">optimization = api.ReadOptimization(optimization_id)</pre>
</div>

<?php include(DOCS_RESOURCES."/qc-api/get-optimization-id-in-research.html"); ?>

<p>The <code>ReadOptimization</code> method returns an <code>Optimization</code> object, which have the following attributes:</p>
<div data-tree='QuantConnect.Api.Optimization'></div>