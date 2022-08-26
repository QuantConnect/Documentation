<?php
$getDeployText = function($compileProjectsLink, $selectNodeLink, $defineAlgorithmSettingsLink)
{
	echo "
<p>You need to <a href='{$compileProjectsLink}'>compile the project</a>, <a href='{$selectNodeLink}'>select a node</a>, and <a href='{$defineAlgorithmSettingsLink}'>define the algorithm settings</a> before you can deploy live algorithms.</p>

<p>To deploy live algorithms, call the <code>CreateLiveAlgorithm</code> method.</p>

<div class='section-example-container'>
    <pre class='csharp'>var liveAlgorithm = api.CreateLiveAlgorithm(projectId, compileId, nodeId, liveSettings);</pre>
    <pre class='python'>new_live_algorithm = api.CreateLiveAlgorithm(project_id, compile_id, node_id, live_settings)</pre>
</div>

<p>By default, LEAN uses the latest master branch. If the latest master branch causes issues with your live deployment, pass a LEAN version to the <code>CreateLiveAlgorithm</code> method.</p>

<div class='section-example-container'>
    <pre class='csharp'>var liveAlgorithm = api.CreateLiveAlgorithm(projectId, compileId, nodeId, liveSettings, versionId);</pre>
    <pre class='python'>new_live_algorithm = api.CreateLiveAlgorithm(project_id, compile_id, node_id, live_settings, version_id)</pre>
</div>

<p>The <code>CreateLiveAlgorithm</code> method returns a <code>LiveAlgorithm</code> object, which have the following attributes:</p>
<div data-tree='QuantConnect.Api.LiveAlgorithm'></div>
	";


}
?>