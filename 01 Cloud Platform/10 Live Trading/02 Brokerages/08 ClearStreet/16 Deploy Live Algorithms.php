<?
$brokerageName = "Clear Street";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Enter your Clear Street access token and account ID.</li>" . file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/clearstreet.html");
$postDeploy = "";
$dataProviderDetails = "<p>Clear Street doesn't provide a live data feed, so use the <a href='/docs/v2/cloud-platform/datasets'>QuantConnect data provider</a> or another data provider for the securities you trade.</p>";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
