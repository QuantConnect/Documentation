<?
$brokerageName = "QuantConnect Paper Trading";
$dataFeedName = "Polygon";
$isBrokerage = false;
$brokerageDetails = "";
$dataFeedDetails = "
    <li>Enter your Polygon API key.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Configure credentials for Polygon

Your Polygon API Key:</pre>
</div>

<p>To get your API key, see the <a rel='nofollow' target='_blank' href='https://polygon.io/dashboard/api-keys'>API Keys page</a> on the Polygon website.</p>
    </li>
";
$supportsIQFeed = false;
$requiresSubscription = false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>
