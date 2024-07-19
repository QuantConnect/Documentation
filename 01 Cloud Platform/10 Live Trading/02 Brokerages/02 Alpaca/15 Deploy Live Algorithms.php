<?
$brokerageName = "Alpaca";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Enter your Alpaca account Id and token.</li>";
$authentication .= file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/alpaca.html");
$dataProviderDetails = "<p>In most cases, we suggest using the <a href='/docs/v2/cloud-platform/datasets'>QuantConnect data provider</a>, the <a href='/docs/v2/cloud-platform/datasets/alpaca'>Alpaca data provider</a>, or both. The order you set them in the deployment wizard defines their order of precedence in Lean.</p>";
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
