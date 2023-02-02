<p>To delete an optimization result, call the <code>DeleteOptimization</code> method with the optimization Id.</p>
<div class="section-example-container">
    <pre class="csharp">var response = api.DeleteOptimization(optimizationId);</pre>
    <pre class="python">response = api.DeleteOptimization(optimization_id)</pre>
</div>

<?php include(DOCS_RESOURCES."/qc-api/get-optimization-id-in-research.html"); ?>

<p>The <code>DeleteOptimization</code> method returns a <code>RestResponse</code> object, which have the following attributes:</p>

<div data-tree="QuantConnect.Api.RestResponse"></div>