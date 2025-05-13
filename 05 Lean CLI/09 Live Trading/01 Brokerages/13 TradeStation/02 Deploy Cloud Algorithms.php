<?
$brokerageName = "<a rel='nofollow' target='_blank' href='https://qnt.co/tradestation-signup'>TradeStation</a>";
$dataProviderName=$brokerageName;
$brokerageDetails = "
<li>In the browser window that automatically opens, log in to your TradeStation account.

<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Please open the following URL in your browser to authorize the LEAN CLI.
https://www.quantconnect.com/api/v2/live/auth0/authorize?brokerage=tradestation
Will sleep 5 seconds and retry fetching authorization...
</pre>
</div>
</li>

<li>Enter the TradeStation account ID.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
The TradeStation account Id (11810357, 210NKH33, SIM2829935F, SIM2829934M): SIM2829935F</pre>
</div>
</li>";

$dataProviderDetails = "
<li>In the browser window that automatically opens, log in to your TradeStation account.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Please open the following URL in your browser to authorize the LEAN CLI.
https://www.quantconnect.com/api/v2/live/auth0/authorize?brokerage=tradestation
Will sleep 5 seconds and retry fetching authorization...
</pre>
</div>
</li>";
$isSupported=true;
$supportsCashHoldings=false;
$supportsPositionHoldings=false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>
