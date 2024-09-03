<?
$brokerageName = "Coinbase Advanced Trade";
$dataFeedName = $brokerageName;
$isBrokerage = true;
$brokerageDetails = "
<li>Enter your API name and private key.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
API name: organizations/2c7dhs-a3a3-4acf-aa0c-f68584f34c37/apiKeys/41090ffa-asd2-8080-815f-afaf63747e35
API private key: ****************************************************************************************</pre>
</div>
To create new API credentials, see <a href='/docs/v2/cloud-platform/live-trading/brokerages/coinbase#02-Account-Types'>Account Types</a>.
</li>
";
$dataFeedDetails = "";
$supportsIQFeed = false;
$requiresSubscription = true;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>
