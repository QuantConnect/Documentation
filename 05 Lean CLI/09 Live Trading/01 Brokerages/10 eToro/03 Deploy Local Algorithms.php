<?
$brokerageName = "eToro";
$dataFeedName = "";
$isBrokerage = true;
$brokerageDetails = "
<li>Enter your eToro API key, user key, and environment.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
API key: 0a1b2c3d-4e5f-6a7b-8c9d-0e1f2a3b4c5d
User key: ********************************
Environment (real, demo): demo</pre>
</div>
<p>The user key must belong to the environment you select.</p>
" . file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/etoro.html") . "
</li>";
$dataFeedDetails = "";
$supportsIQFeed = false;
$requiresSubscription = true;
$moduleName = "eToro";
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>
