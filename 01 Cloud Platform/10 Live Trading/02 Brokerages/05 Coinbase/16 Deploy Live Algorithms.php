<?
$brokerageName = "Coinbase";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Enter your Coinbase API key and API secret.</li>" . file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/coinbase.html");
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
