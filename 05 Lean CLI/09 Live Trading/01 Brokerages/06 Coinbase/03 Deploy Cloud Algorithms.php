<?
$brokerageDetails = "
<li>Enter your API key, API secret, and passphrase.</li>
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
API key: 6d3ef5ca2d2fa52e4ee55624b0471261
API secret: ****************************************************************************************
Passphrase: ****************
</div>
<p>To create new API credentials, see the <a href='https://pro.coinbase.com/profile/api' target='_blank' rel='nofollow'>API settings page</a> on the Coinbase website.</p>
";
$brokerageName="Coinbase Advanced Trade";
$isSupported=true;
$supportsCashHoldings=false;
$supportsPositionHoldings=false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>
