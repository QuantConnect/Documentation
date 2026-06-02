<?
$brokerageName = "Webull";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Click the <span class=\"button-name\">Authenticate</span> button.</li>
<li>On the Webull website, log in to your account to grant QuantConnect access to your account information and authorization.</li>
<li>Click the <span class='field-name'>Select Account Id</span> field and then click one of your accounts.</li>";
$postDeploy = "";
$dataProviderDetails = "<p>Webull doesn't provide a live data feed, so use the <a href='/docs/v2/cloud-platform/datasets'>QuantConnect data provider</a> or another data provider for the securities you trade.</p>";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
