<?
$brokerageName = "Public";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Enter your Public secret key and account number.</li>" . file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/public.html");
$postDeploy = "";
$dataProviderDetails = "<p>Public doesn't provide a live data feed, so use a data provider for the securities you trade. The <a href='/docs/v2/cloud-platform/datasets/quantconnect'>QuantConnect data provider</a> supplies US Equity, Equity Option, Index Option, and Crypto data. It supplies <a href='/docs/v2/cloud-platform/datasets/quantconnect/us-indices'>SPX and VIX Index data</a> but not other Index data, so if you trade Index Options on other indices, such as NDX, add a data provider that covers the underlying index, such as <a href='/docs/v2/cloud-platform/datasets/polygon'>Polygon</a>.</p>";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
