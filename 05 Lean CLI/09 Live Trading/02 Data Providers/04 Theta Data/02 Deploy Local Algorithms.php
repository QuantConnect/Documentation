<?
$brokerageName = "QuantConnect Paper Trading";
$dataFeedName = "ThetaData";
$isBrokerage = false;

$brokerageDetails = "";

$dataFeedDetails = "
    <li>(Optional) Enter the host of the ThetaData Client. 
    <br>
    <p>The default host is <span class='live-url'>ws://host.docker.internal:25520/v1/events</span> or <span class='live-url'>http://host.docker.internal:25510</span>.</p>
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
The host of ThetaData Client [ws://host.docker.internal:25520/v1/events]:
The host of ThetaData Client [http://host.docker.internal:25510]:</pre>
</div>
    </li>

    <li>Enter your Theta Data subscription plan. 
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
ThetaData subscription price plan (Free, Value, Standard, Pro):</pre>
</div>
    </li>
";
$supportsIQFeed = false;
$requiresSubscription = false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>
