<?php
$brokerageDetails = "
<li>Enter your API key id and secret.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
API key: bbbMsqbxjytVM9cGvnLpKguz9rZf2T5qACxaVx7E8Mm
Secret key: *******************************************</div>
To create new API credentials, see the <a rel='nofollow' href='https://www.bitfinex.com/api' target='_blank'>API Management page</a> on the Bitfinex website.
</li>
";
$brokerageName="Bitfinex";
$isSupported=true;
$supportsCashHoldings=false;
$supportsPositionHoldings=false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>