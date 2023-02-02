<p>To stop an algorithm but maintain its holdings, call the <code>StopLiveAlgorithm</code> method with the project ID.</p>

<div class="section-example-container">
    <pre class="csharp">var response = api.StopLiveAlgorithm(projectId);</pre>
    <pre class="python">response = api.StopLiveAlgorithm(project_id)</pre>
</div>

<?php include(DOCS_RESOURCES."/qc-api/get-project-id-in-research.html"); ?>

<p>The <code>StopLiveAlgorithm</code> method returns a <code>RestResponse</code> object, which have the following attributes:</p>
<div data-tree='QuantConnect.Api.RestResponse'></div>
