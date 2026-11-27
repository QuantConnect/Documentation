<?
$brokerageName = "Clear Street";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<p>The <span class='button-name'>Clear Street OAuth2 (Beta)</span> option connects through your Clear Street sign-in, and the <span class='button-name'>Clear Street</span> option connects with an access token. If you select <span class='button-name'>Clear Street OAuth2 (Beta)</span>, follow these steps:</p>
<ol>
    <li>Click <span class='button-name'>Authenticate</span>.</li>
    <li>Log in to Clear Street and authorize QuantConnect to access your accounts.</li>
    <li>Back in the wizard, click the account field and then click the account you want to trade.</li>
</ol>
<p>If you select <span class='button-name'>Clear Street</span>, enter your Clear Street access token and account ID.</p>" . file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/clear-street.html");
$postDeploy = "";
$dataProviderDetails = "<p>Clear Street doesn't provide a live data feed, so use the <a href='/docs/v2/cloud-platform/datasets'>QuantConnect data provider</a> or another data provider for the securities you trade.</p>";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
