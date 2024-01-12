<?
$brokerageName = "Coinbase Advanced Trade";
$dataFeedName = "";
$isBrokerage = true;
$brokerageDetails = "
<li>Enter your API key and API secret.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
API key: 6d3ef5ca2d2fa52e4ee55624b0471261
API secret: ****************************************************************************************</pre>
</div>
To create new API credentials, see the <a href='https://www.coinbase.com/settings/api' target='_blank' rel='nofollow'>API settings page</a> on the Coinbase website.
</li>
";
$dataFeedDetails = "";
$supportsIQFeed = false;
$requiresSubscription = true;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>
