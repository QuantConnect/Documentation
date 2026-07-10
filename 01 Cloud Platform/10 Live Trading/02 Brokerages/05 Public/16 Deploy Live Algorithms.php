<?
$brokerageName = "Public";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Enter your Public secret key and account number.</li>" . file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/public.html");
$postDeploy = "";
$dataProviderDetails = "<p>Public doesn't provide a live data feed, so use a data provider for the securities you trade. The <a href='/docs/v2/cloud-platform/datasets/quantconnect'>QuantConnect data provider</a> supplies US Equity and Crypto data. For Equity Options, Index, and Index Options data, use the <a href='/docs/v2/cloud-platform/datasets/polygon'>Polygon data provider</a> or a data feed from another brokerage.</p>";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
