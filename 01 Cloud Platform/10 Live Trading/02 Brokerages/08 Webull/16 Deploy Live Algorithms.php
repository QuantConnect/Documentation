<?
$brokerageName = "Webull";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Enter your Webull App Key, App Secret, and account ID.</li>" . file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/webull.html");
$postDeploy = "";
$dataProviderDetails = "<p>Webull doesn't provide a live data feed, so use the <a href='/docs/v2/cloud-platform/datasets'>QuantConnect data provider</a> or another data provider for the securities you trade.</p>";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
