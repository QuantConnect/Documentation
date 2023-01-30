<?
$brokerageName = "Bitfinex";
$dataFeedName = "";
$isBrokerage = true;
$brokerageDetails = "
<li>Enter your API key id and secret.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
API key: bbbMsqbxjytVM9cGvnLpKguz9rZf2T5qACxaVx7E8Mm
API secret: *******************************************</pre>
</div>
To create new API credentials, see the <a rel='nofollow' href='https://www.bitfinex.com/api' target='_blank'>API Management page</a> on the Bitfinex website.
</li>
";

$dataFeedDetails = "";
$supportsIQFeed = false;
$requiresSubscription = true;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>
