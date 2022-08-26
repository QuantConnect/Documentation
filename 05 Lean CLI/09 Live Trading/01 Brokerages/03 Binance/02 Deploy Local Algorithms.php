<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");

$brokerageName = "Binance or Binance US";
$dataFeedName = "";
$isBrokerage = true;
$brokerageDetails = "
<li>Enter the exchange to use.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Binance Exchange (Binance, BinanceUS): BinanceUS</pre>
</div>
</li>

<li>Enter the environment to use.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Use the testnet? (live, paper): live</pre>
</div>
</li>

<li>Enter your API key and API secret.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
API key: 6d3ef5ca2d2fa52e4ee55624b0471261
API secret: ********************************</pre>
</div>
To create a new API key, see the API Management page on <a rel='nofollow' href='https://www.binance.com/en/my/settings/api-management' target='_blank'>Binance</a> or <a rel='nofollow' href='https://www.binance.us/en/usercenter/settings/api-management' target='_blank'>Binance US</a>.
</li>
";

$dataFeedDetails = "";
$supportsIQFeed = false;
$requiresSubscription = true;
$moduleName = "Binance";

$getDeployLocalAlgorithmsText($brokerageName, $dataFeedName, $isBrokerage, $brokerageDetails, $dataFeedDetails, $supportsIQFeed, $requiresSubscription, $moduleName);
?>
