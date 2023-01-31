<?
$brokerageDetails = "
<li>Enter your API key and API secret.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
API key: 
Secret key: </pre>
</div>
To get your API credentials, see the <a target='_blank' rel='nofollow' href='https://www.kraken.com/u/security/api'>API Management Settings</a> page on the Kraken website.
</li>

<li>Enter your verification tier.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Select the Verification Tier (Starter, Intermediate, Pro):</pre>
</div>
For more information about verification tiers, see <a href='https://support.kraken.com/hc/en-us/articles/360001395743-Verification-levels-explained' target='_blank' rel='nofollow'>Verification levels explained</a> on the Kraken website.
</li>
";
$brokerageName="Kraken";
$isSupported=true;
$supportsCashHoldings=false;
$supportsPositionHoldings=false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>