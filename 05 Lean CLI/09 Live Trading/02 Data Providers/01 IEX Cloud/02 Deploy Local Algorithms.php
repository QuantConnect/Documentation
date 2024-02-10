<?
$brokerageName = "QuantConnect Paper Trading";
$dataFeedName = "IEX Cloud";
$isBrokerage = false;

$brokerageDetails = "";

$dataFeedDetails = "
    <li>Enter your IEX Cloud API Key. 
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Configure credentials for IEX
Your iexcloud.io API token publishable key: </pre>
</div>
    </li>

    <li>Enter your price plan.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
IEX Cloud Price plan (Launch, Grow, Enterprise): </pre>
</div>
    </li>
";
$supportsIQFeed = true;
$requiresSubscription = false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>
