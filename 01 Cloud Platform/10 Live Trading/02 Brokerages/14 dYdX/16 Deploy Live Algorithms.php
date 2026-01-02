<?
$brokerageName = "dYdX Exchange";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Enter your private key, wallet address, and sub-account number</li>";
$authentication .= file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/dydx.html");
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>