<?
$brokerageName = "Kraken Exchange";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Enter your Kraken API secret and key.</li>
<p>Gather your API credentials from the <a rel='nofollow' target='_blank' href='https://www.kraken.com/u/security/api'>API Management Settings</a> page on the Kraken website. Your account details are not saved on QuantConnect.</p>
<li>Click the <span class='field-name'>Verification Tier</span> field and then click your verification tier from the drop-down menu.</li>
<p>For more information about verification tiers, see <a href='https://support.kraken.com/hc/en-us/articles/360001395743-Verification-levels-explained' target='_blank' rel='nofollow'>Verification levels explained</a> on the Kraken website.</p>";
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>