<?
$brokerageDetails = "
<li>In the browser window that automatically opens, log in to your Webull account.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Please open the following URL in your browser to authorize the LEAN CLI.
https://www.quantconnect.com/api/v2/live/auth0/authorize?brokerage=webull
Will sleep 5 seconds and retry fetching authorization...
</pre>
</div>
</li>

<li>Enter the Webull account ID.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
The Webull account Id: 12345678</pre>
</div>
</li>";
$dataProviderDetails = "";
$brokerageName="Webull";
$isSupported=true;
$supportsCashHoldings=false;
$supportsPositionHoldings=false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>
