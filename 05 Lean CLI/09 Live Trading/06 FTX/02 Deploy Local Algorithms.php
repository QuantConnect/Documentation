<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");

$brokerageName = "FTX or FTX US";
$dataFeedName = "";
$isBrokerage = true;
$brokerageDetails = "
<li>Enter the exchange to use.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
FTX Exchange (FTX, FTXUS):</pre>
</div>
</li>

<li>Enter your API key, API secret, and account tier. To create a new API credentials and to check your account tier, see your Profile page on the <a href='https://ftx.com/profile' target='_blank' rel='nofollow'>FTX</a> or <a href='https://ftx.us/profile' target='_blank' rel='nofollow'>FTX US</a> website. If your account tier changes after you deploy the algorithm, stop the algorithm and then redeploy it to correct the account tier.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
API key: 
API secret: 
Select the Account Tier (Tier1, Tier2, Tier3, Tier4, Tier5, Tier6, VIP1, VIP2, VIP3, MM1, MM2, MM3):</pre>
</div>
</li>
";
$dataFeedDetails = "";
$supportsIQFeed = false;
$requiresSubscription = true;
$moduleName = "FTX";

$getDeployLocalAlgorithmsText($brokerageName, $dataFeedName, $isBrokerage, $brokerageDetails, $dataFeedDetails, $supportsIQFeed, $requiresSubscription, $moduleName);
?>
