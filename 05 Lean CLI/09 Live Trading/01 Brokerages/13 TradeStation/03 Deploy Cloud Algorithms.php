<?
$brokerageName = "TradeStation";
$dataFeedName = "";
$isBrokerage = true;
$brokerageDetails = "
<li>LEAN CLI opens your browser for authorization. Login to TradeStation.

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

$dataFeedDetails = "
<li>LEAN CLI opens your browser for authorization. Login to TradeStation if necessary.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Please open the following URL in your browser to authorize the LEAN CLI.
https://www.quantconnect.com/api/v2/live/auth0/authorize?brokerage=tradestation
Will sleep 5 seconds and retry fetching authorization...
</pre>
</div>
</li>";
$supportsIQFeed = true;
$requiresSubscription = true;
$moduleName = "TradeStation";
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>