<?
$brokerageName = "Public";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Enter your Public secret key and account number.</li>" . file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/public.html");
$postDeploy = "";
$dataProviderDetails = "<p>Public doesn't provide a live data feed, so use the <a href='/docs/v2/cloud-platform/datasets'>QuantConnect data provider</a> or another data provider for the securities you trade.</p>";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
