<?php
include(DOCS_RESOURCES."/brokearges/cli-deployment/deploy-cloud-algorithms.php");

$brokerageDetails = "
<li>Enter your API key id and secret. You can generate new API credentials on the <a href='https://www.bitfinex.com/api' target='_blank'>API Management page</a>.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
API key: bbbMsqbxjytVM9cGvnLpKguz9rZf2T5qACxaVx7E8Mm
Secret key: *******************************************</div>
</li>
";

$getDeployCloudAlgorithmsText("Bitfinex", true, $brokerageDetails);
?>