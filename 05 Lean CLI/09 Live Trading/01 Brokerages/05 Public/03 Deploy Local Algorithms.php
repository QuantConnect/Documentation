<?
$brokerageName = "Public";
$dataFeedName = "";
$isBrokerage = true;
$brokerageDetails = "
<li>Enter your Public secret key and account number.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Secret key: ********************************
Account number: 5PY12345</pre>
</div>
" . file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/public.html") . "
</li>";
$dataFeedDetails = "";
$supportsIQFeed = false;
$requiresSubscription = true;
$moduleName = "Public";
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>
