<?
$brokerageDetails = "
<li>Enter the environment to use. Enter <code>Trade</code> for fxTrade or <code>Practice</code> for fxTrade Practice.</li>
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Environment? (Practice, Trade): </pre>
</div>

<li>Enter your OANDA account ID.</li>
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Account id: 001-011-5838423-001</pre>
</div>
<p>To get your account ID, see your <a href='https://www.oanda.com/account/statement/' target='_blank'>Account Statement page</a> on the OANDA website.</p>

<li>Enter your OANDA API token.</li>
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
API token: ****************</pre>
</div>
<p>To create a token, see the <a href='https://www.oanda.com/account/tpa/personal_token' target='_blank'>Manage API Access page</a> on the OANDA website.</p>
";
$brokerageName="Oanda";
$isSupported=true;
$supportsCashHoldings=false;
$supportsPositionHoldings=false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>
