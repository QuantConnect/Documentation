<?
$brokerageName = "Clear Street";
$dataFeedName = "";
$isBrokerage = true;
$brokerageDetails = "
<li>Enter your Clear Street access token and account ID.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Access token: ********************************
Account id: 125046</pre>
</div>
" . file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/clearstreet.html") . "
</li>";
$dataFeedDetails = "";
$supportsIQFeed = false;
$requiresSubscription = true;
$moduleName = "Clear Street";
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>
