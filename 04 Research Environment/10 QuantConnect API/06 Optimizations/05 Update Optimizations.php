<p>To update the name of an optimization, call the <code>UpdateOptimization</code> method with the optimization Id and the new optimization name.</p>
<div class="section-example-container">
    <pre class="csharp">var response = api.UpdateOptimization(optimizationId, "New Name");</pre>
    <pre class="python">response = api.UpdateOptimization(optimization_id, "New Name")</pre>
</div>

<?php include(DOCS_RESOURCES."/qc-api/get-optimization-id-in-research.html"); ?>

<p>The <code>UpdateOptimization</code> method returns a <code>RestResponse</code> object, which have the following attributes:</p>

<div data-tree="QuantConnect.Api.RestResponse"></div>