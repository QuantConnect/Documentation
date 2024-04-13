<p>To get the results of a live algorithm, call the <code>ReadLiveAlgorithm</code> method with the project Id and deployment ID.</p>

<div class="section-example-container">
    <pre class="csharp">#load "../Initialize.csx"
#load "../QuantConnect.csx"

using QuantConnect;
using QuantConnect.Api;

var liveAlgorithm = api.ReadLiveAlgorithm(projectId, deployId);</pre>
    <pre class="python">live_algorithm = api.read_live_algorithm(project_id, deploy_id)</pre>
</div>

<?php include(DOCS_RESOURCES."/qc-api/get-project-id-in-research.html"); ?>
<?php include(DOCS_RESOURCES."/qc-api/get-deployment-id-in-research.html"); ?>

<p>The <code>ReadLiveAlgorithm</code> method returns a <code>LiveAlgorithmResults</code> object, which have the following attributes:</p>
<div data-tree='QuantConnect.Api.LiveAlgorithmResults'></div>