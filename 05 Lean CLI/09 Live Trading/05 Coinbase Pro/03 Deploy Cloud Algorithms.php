<?php
include(DOCS_RESOURCES."/brokearges/cli-deployment/deploy-cloud-algorithms.php");

$brokerageDetails = "
<li>Enter your API key, API secret, and passphrase. You can generate new API credentials on the <a href='https://pro.coinbase.com/profile/api' target='_blank'>API settings page</a>.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
API key: 6d3ef5ca2d2fa52e4ee55624b0471261
API secret: ****************************************************************************************
Passphrase: ****************
</div>
</li>
";

$getDeployCloudAlgorithmsText("Coinbase Pro", true, $brokerageDetails);
?>