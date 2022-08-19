<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");

$brokerageDetails = "
<li>Enter the exchange to use.
<div class='cli section-example-container'>
<pre>$ lean cloud live deploy \"My Project\" --push --open
Binance Exchange (Binance, BinanceUS): </pre>
</div>
</li>


<li>Enter the environment to use.
<div class='cli section-example-container'>
<pre>$ lean cloud live deploy \"My Project\" --push --open
Environment (live, paper): </pre>
</div>
</li>

<li>Enter your Binance API key and secret.
<div class='cli section-example-container'>
<pre>$ lean cloud live deploy \"My Project\" --push --open
API key: wL1waeOC7VD447skCFeiat9pP3r1uKXfYomGg43uyCOgzl8xsI9SZsX97AXP4zWv
API secret: ****************************************************************</pre>
</div>
To create a new API key, see the API Management page on <a rel='nofollow' href='https://www.binance.com/en/my/settings/api-management' target='_blank'>Binance</a> or <a rel='nofollow' href='https://www.binance.us/en/usercenter/settings/api-management' target='_blank'>Binance US</a>.
</li>
";

$getDeployCloudAlgorithmsText("Binance", true, $brokerageDetails);
?>
