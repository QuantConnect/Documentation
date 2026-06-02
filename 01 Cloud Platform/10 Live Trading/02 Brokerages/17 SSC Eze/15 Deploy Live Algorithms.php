<?
$brokerageName = "SS&C Eze";
$cashState = true;
$holdingsState = false;
$secondBullet = "";
$authentication = file_get_contents(DOCS_RESOURCES."/brokerages/eze/deploy-steps.php");
$postDeploy = "";
$dataProviderDetails = "<p>In most cases, we suggest using the <a href='/docs/v2/cloud-platform/datasets'>QuantConnect data provider</a>, a third-party data provider such as <a href='/docs/v2/cloud-platform/datasets/polygon'>Polygon data provider</a>, or both. The order you set them in the deployment wizard defines their order of precedence in Lean.</p>";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>