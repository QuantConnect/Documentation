<?
$brokerageName = "Webull";
$dataFeedName = "";
$isBrokerage = true;
$brokerageDetails = "
<li>Enter your Webull App Key, App Secret, and account ID.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
App key: 0a1b2c3d4e5f6a7b8c9d0e1f2a3b4c5d
App secret: *******************************
Account id: 12345678</pre>
</div>
" . file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/webull.html") . "
</li>";
$dataFeedDetails = "";
$supportsIQFeed = false;
$requiresSubscription = true;
$moduleName = "Webull";
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>
