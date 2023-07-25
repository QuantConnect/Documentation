<?
$brokerageDetails = "
<li>Enter the exchange to use.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Binance Exchange (Binance, BinanceUS, Binance-USDM-Futures, Binance-COIN-Futures): BinanceUS</pre>
</div>
</li>

<li>Enter your Binance API key and secret.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
API key: wL1waeOC7VD447skCFeiat9pP3r1uKXfYomGg43uyCOgzl8xsI9SZsX97AXP4zWv
API secret: ****************************************************************</pre>
</div>
To create new credentials, see <a rel='nofollow' href='https://www.binance.com/en/support/faq/how-to-create-api-keys-on-binance-360002502072' target='_blank'>How to Create API Keys on Binance</a>.
</li>

<li>Enter the environment to use.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Use the testnet? (live, paper): </pre>
</div>
</li>
";
$brokerageName="Binance";
$isSupported=true;
$supportsCashHoldings=false;
$supportsPositionHoldings=false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>