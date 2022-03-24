<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");


$brokerageDetails = "
<li>Enter your API key and API secret. You can gather these credentials from the <a target='_blank' rel='nofollow' href='https://www.kraken.com/u/security/api'>API Management Settings</a> page on the Kraken website.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
API key: 
API secret: </pre>
</div>
</li>

<li>Enter your verification tier. For more information about verification tiers, see <a href='https://support.kraken.com/hc/en-us/articles/360001395743-Verification-levels-explained' target='_blank' rel='nofollow'>Verification levels explained</a> on the Kraken website.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Select the Verification Tier:
1) Starter
2) Intermediate
3) Pro
Enter an option:</pre>
</div>
</li>
";
$dataFeedDetails = "";
$supportsIQFeed = false;
$requiresSubscription = true;

$getDeployLocalAlgorithmsText("Kraken", $brokerageDetails, $dataFeedDetails, $supportsIQFeed, $requiresSubscription);
?>