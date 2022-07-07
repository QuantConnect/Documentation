<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");

$brokerageName = "Kraken";
$dataFeedName = "";
$isBrokerage = true;
$brokerageDetails = "
<li>Enter your API key and API secret.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
API key: 
API secret: </pre>
</div>
To get your API credentials, see the <a target='_blank' rel='nofollow' href='https://www.kraken.com/u/security/api'>API Management Settings</a> page on the Kraken website.
</li>

<li>Enter your verification tier.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Select the Verification Tier:
1) Starter
2) Intermediate
3) Pro
Enter an option:</pre>
</div>
For more information about verification tiers, see <a href='https://support.kraken.com/hc/en-us/articles/360001395743-Verification-levels-explained' target='_blank' rel='nofollow'>Verification levels explained</a> on the Kraken website.
</li>
";
$dataFeedDetails = "";
$supportsIQFeed = false;
$requiresSubscription = true;

$getDeployLocalAlgorithmsText($brokerageName, $dataFeedName, $isBrokerage, $brokerageDetails, $dataFeedDetails, $supportsIQFeed, $requiresSubscription);
?>
