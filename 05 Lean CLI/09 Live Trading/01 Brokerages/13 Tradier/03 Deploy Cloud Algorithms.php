<?
$brokerageDetails = "
<li>Enter your Tradier account Id and access token.</li>
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Account id: VA000001
Access token: ****************</pre>
</div>
<p>To get these credentials, see your <a href='https://dash.tradier.com/settings/api' target='_blank' rel='nofollow'>Settings/API Access page</a> on the Tradier website.</p>

<li>Enter whether the developer sandbox should be used.</li>
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Use the developer sandbox? (live, paper): </pre>
</div>
";
$brokerageName="Tradier";
$isSupported=true;
$supportsCashHoldings=false;
$supportsPositionHoldings=false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>
