<?
$brokerageName = "TradeStation";
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

<li>Enter the environment to use.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Live or Paper environment? (live, paper): live</pre>
</div>
</li>

<li>Enter the account type to use.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Trade Station account type (Cash, Margin, Futures, DVP): Margin</pre>
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
