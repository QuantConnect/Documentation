<?
$brokerageDetails = "
<li>Enter your API key and API secret.</li>
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
API key: 6d3ef5ca2d2fa52e4ee55624b0471261
API secret: ****************************************************************************************</pre>
</div>
<p>To create new API credentials, see the <a href='https://www.coinbase.com/settings/api' target='_blank' rel='nofollow'>API settings page</a> on the Coinbase website.</p>
";
$brokerageName="Coinbase Advanced Trade";
$isSupported=true;
$supportsCashHoldings=false;
$supportsPositionHoldings=false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>
