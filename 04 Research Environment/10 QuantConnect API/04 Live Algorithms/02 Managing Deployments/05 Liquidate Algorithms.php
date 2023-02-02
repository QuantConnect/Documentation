<p>To liquidate and stop an algorithm, call the <code>LiquidateLiveAlgorithm</code> method with the project ID.</p>
<div class="section-example-container">
    <pre class="csharp">var response = api.LiquidateLiveAlgorithm(projectId);</pre>
    <pre class="python">response = api.LiquidateLiveAlgorithm(project_id)</pre>
</div>

<?php include(DOCS_RESOURCES."/qc-api/get-project-id-in-research.html"); ?>

<p>The <code>LiquidateLiveAlgorithm</code> method returns a <code>RestResponse</code> object, which have the following attributes:</p>
<div data-tree='QuantConnect.Api.RestResponse'></div>