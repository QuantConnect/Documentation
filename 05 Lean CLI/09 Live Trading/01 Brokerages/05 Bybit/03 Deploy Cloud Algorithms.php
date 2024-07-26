<?
$brokerageDetails = "
<li>Enter your API key and secret.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
API key: bbbMsqbxjytVM9cGvnLpKguz9rZf2T5qACxaVx7E8Mm
API secret: *******************************************</pre>
</div>
" . file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/bitfinex.html") . "
</li>

<li>Enter your VIP level.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
VIP Level (VIP0, VIP1, VIP2, VIP3, VIP4, VIP5, SupremeVIP, Pro1, Pro2, Pro3, Pro4, Pro5): VIP0</pre>
</div>
<p>For more information about VIP levels, see <a href='https://www.bybit.com/en/help-center/article/FAQ-VIP-Program' target='_blank' rel='nofollow'>FAQ — Bybit VIP Program</a> on the Bybit website.</p>
</li>
";
$brokerageName="Bybit";
$isSupported=true;
$supportsCashHoldings=false;
$supportsPositionHoldings=true;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>
