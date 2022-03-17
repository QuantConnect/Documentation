<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");


$brokerageDetails = "
<li>Enter your API key id and secret. You can generate new API credentials on the <a href='https://www.bitfinex.com/api' target='_blank'>API Management page</a>.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
API key: bbbMsqbxjytVM9cGvnLpKguz9rZf2T5qACxaVx7E8Mm
API secret: *******************************************</pre>
</div>
</li>
";

$dataFeedDetails = "";
$supportsIQFeed = false;

$getDeployLocalAlgorithmsText("Bitfinex", $brokerageDetails, $dataFeedDetails, $supportsIQFeed);
?>