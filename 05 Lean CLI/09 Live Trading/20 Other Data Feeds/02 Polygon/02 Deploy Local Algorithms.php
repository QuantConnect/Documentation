<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");

$brokerageName = "QuantConnect Paper Trading";
$dataFeedName = "Polygon";
$isBrokerage = false;

$brokerageDetails = "";

$dataFeedDetails = "
    <li>Enter your Polygon API key.
<div class='cli section-example-container'>
<pre>$ lean live deploy \"My Project\"
Configure credentials for Polygon Data Feed

Your Polygon data feed API Key:</pre>
</div>

<p>To get your API key, see the <a rel='nofollow' target='_blank' href='https://polygon.io/dashboard/api-keys'>API Keys page</a> on the Polygon website.</p>
    </li>
";

$supportsIQFeed = false;
$requiresSubscription = false;

$getDeployLocalAlgorithmsText($brokerageName, $dataFeedName, $isBrokerage, $brokerageDetails, $dataFeedDetails, $supportsIQFeed, $requiresSubscription);
?>
