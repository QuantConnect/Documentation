<?
$brokerageName = "Eze";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "";
$postDeploy = "";
$dataProviderDetails = "<p>In most cases, we suggest using the <a href='/docs/v2/cloud-platform/datasets'>QuantConnect data provider</a>, the <a href='/docs/v2/cloud-platform/datasets/eze'>Eze data provider</a>, or both. The order you set them in the deployment wizard defines their order of precedence in Lean.</p>";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>