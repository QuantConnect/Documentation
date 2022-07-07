<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");

$brokerageName = "Coinbase Pro";
$dataFeedName = "";
$isBrokerage = true;
$brokerageDetails = "
<li>Enter your API key, API secret, and passphrase. You can generate new API credentials on the <a href='https://pro.coinbase.com/profile/api' target='_blank'>API settings page</a>.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
API key: 6d3ef5ca2d2fa52e4ee55624b0471261
API secret: ****************************************************************************************
Passphrase: ****************</pre>
</div>
</li>

<li>Enter whether the sandbox should be used.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Use the sandbox? [y/N]: n</pre>
</div>
</li>

";
$dataFeedDetails = "";
$supportsIQFeed = false;
$requiresSubscription = false;

$getDeployLocalAlgorithmsText($brokerageName, $dataFeedName, $isBrokerage, $brokerageDetails, $dataFeedDetails, $supportsIQFeed, $requiresSubscription);
?>
