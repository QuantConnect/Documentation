<?
$brokerageName = "Bybit Exchange";
$cashState = false;
$holdingsState = true;
$secondBullet = "";
$authentication = "<li>Enter your API key and secret.</li>" . file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/bitfinex.html") . "
<li>Click the <span class='field-name'>VIP Level</span> field and then click your level from the drop-down menu.</li>
<p>For more information about VIP levels, see <a href='https://www.bybit.com/en/help-center/article/FAQ-VIP-Program' target='_blank' rel='nofollow'>FAQ — Bybit VIP Program</a> on the Bybit website.</p>";
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>