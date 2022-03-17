<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");

$brokerageDetails = "
<li>Enter your Binance API key and secret. You can generate new API credentials on your <a href='https://www.binance.com/en/my/settings/api-management' target='_blank'>API Settings Management</a> page if you want to trade use the production environment, or on <a href='https://testnet.binance.vision/' target='_blank'>Binance Testnet</a> if you want to use the demo environment.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
API key: wL1waeOC7VD447skCFeiat9pP3r1uKXfYomGg43uyCOgzl8xsI9SZsX97AXP4zWv
API secret: ****************************************************************</pre>
</div>
</li>

<li>Enter which environment you want to use. You can either choose <code>demo</code> for the testnet or <code>real</code> for the production environment.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Environment (demo, real): demo</pre>
</div>
</li>
";

$getDeployCloudAlgorithmsText("Binance", true, $brokerageDetails);
?>