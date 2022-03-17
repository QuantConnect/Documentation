<?php
include(DOCS_RESOURCES."/brokearges/cli-deployment/deploy-local-algorithms.php");

$brokerageDetails = "
<li>Enter your API key and API secret. You can create a new API key on the <a href='https://www.binance.com/en/my/settings/api-management' target='_blank'>API Management page</a>.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
API key: 6d3ef5ca2d2fa52e4ee55624b0471261
API secret: ********************************</pre>
</div>
</li>

<li>Enter whether the testnet should be used.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Use the testnet? [y/N]: n</pre>
</div>
</li>
";

$dataFeedDetails = "";
$supportsIQFeed = false;

$getDeployLocalAlgorithmsText("Binance", $brokerageDetails, $dataFeedDetails, $supportsIQFeed);
?>