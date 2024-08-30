<?
$brokerageName = "Coinbase";
$cashState = false;
$holdingsState = true;
$secondBullet = "";
$authentication = "<li>Enter your Coinbase API Name and API Private Key.</li>" . file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/coinbase.html");
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
