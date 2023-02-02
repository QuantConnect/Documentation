<p>To abort an optimization, call the <code>AbortOptimization</code> method with the optimization Id.</p>
<div class="section-example-container">
    <pre class="csharp">var response = api.AbortOptimization(optimizationId);</pre>
    <pre class="python">response = api.AbortOptimization(optimization_id)</pre>
</div>

<?php include(DOCS_RESOURCES."/qc-api/get-optimization-id-in-research.html"); ?>

<p>The <code>AbortOptimization</code> method returns a <code>RestResponse</code> object, which have the following attributes:</p>

<div data-tree="QuantConnect.Api.RestResponse"></div>