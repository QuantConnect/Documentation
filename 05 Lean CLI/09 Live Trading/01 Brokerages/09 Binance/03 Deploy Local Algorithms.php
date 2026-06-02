<?
$brokerageName = "Binance or Binance US";
$dataFeedName = "";
$isBrokerage = true;
$brokerageDetails = "
<li>Enter the exchange to use.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Binance Exchange (Binance, BinanceUS, Binance-USDM-Futures, Binance-COIN-Futures): BinanceUS</pre>
</div>
</li>

<li>Enter your API key and API secret.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
API key: 6d3ef5ca2d2fa52e4ee55624b0471261
API secret: ********************************</pre>
</div>
To create new credentials, see <a rel='nofollow' href='https://www.binance.com/en/support/faq/how-to-create-api-keys-on-binance-360002502072' target='_blank'>How to Create API Keys on Binance</a>.
</li>

<li>Enter the environment to use.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Use the testnet? (live, paper): live</pre>
</div>
</li>
";

$dataFeedDetails = "";
$supportsIQFeed = false;
$requiresSubscription = true;
$moduleName = "Binance";
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>
