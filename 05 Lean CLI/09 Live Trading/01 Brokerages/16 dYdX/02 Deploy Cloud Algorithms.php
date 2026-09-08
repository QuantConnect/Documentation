<?
$brokerageDetails = "
<li>Enter your API key, wallet address, and your suaccount number.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Should be in hex format starting with '0x'.
API private key (hex): 0x****************************************************************
Wallet address:
Subaccount number [0]:</pre>
</div>
To create new API credentials, see the <a href='https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/dydx#02-Account-Types'>Account Types</a> on the dYdX documentation on QuantConnect.
</li>
<li>Enter the environment to use.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Use the developer sandbox? (live, paper): live</pre>
</div>
</li>";
$brokerageName="dYdX";
$dataProviderName=$brokerageName;
$isSupported=true;
$supportsCashHoldings=false;
$supportsPositionHoldings=false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>
