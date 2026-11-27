<?
$brokerageName = "Clear Street";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>In the <span class='page-section-name'>Clear Street Authentication</span> section, click <span class='button-name'>Authenticate</span>.</li>
<li>Log in to Clear Street and authorize QuantConnect to access your accounts.</li>
<li>Back in the wizard, click the account field and then click the account you want to trade.</li>";
$postDeploy = "";
$dataProviderDetails = "<p>Clear Street doesn't provide a live data feed, so use the <a href='/docs/v2/cloud-platform/datasets'>QuantConnect data provider</a> or another data provider for the securities you trade.</p>";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
