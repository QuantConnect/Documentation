<?
$brokerageDetails = "
<li>Enter your TD Ameritrade credentials.</li>
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\"
API key:
OAuth Access Token: 
Account number: </pre>
</div>
<p>To get your account credentials, see <a href='https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/td-ameritrade#02-Account-Types'>Account Types</a>.</p>
";
$brokerageName="TD Ameritrade";
$isSupported=true;
$supportsCashHoldings=false;
$supportsPositionHoldings=false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>
