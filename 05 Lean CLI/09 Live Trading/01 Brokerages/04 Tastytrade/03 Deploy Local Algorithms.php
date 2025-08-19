<?
$brokerageName = "<a rel='nofollow' target='_blank' href='https://qnt.co/tastytrade'>tastytrade</a>";
$dataFeedName = "";
$isBrokerage = true;
$brokerageDetails = "
<li>LEAN CLI opens your browser for authorization. Login to tastytrade.

<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Please open the following URL in your browser to authorize the LEAN CLI.
https://www.quantconnect.com/api/v2/live/auth0/authorize?brokerage=tastytrade
Will sleep 5 seconds and retry fetching authorization...
</pre>
</div>
</li>

<li>Enter the tastytrade account ID.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
The tastytrade account Id (11810357, 210NKH33, SIM2829935F, SIM2829934M): SIM2829935F</pre>
</div>
</li>";

$dataFeedDetails = "
<li>LEAN CLI opens your browser for authorization. Login to tastytrade if necessary.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Please open the following URL in your browser to authorize the LEAN CLI.
https://www.quantconnect.com/api/v2/live/auth0/authorize?brokerage=tastytrade
Will sleep 5 seconds and retry fetching authorization...
</pre>
</div>
</li>";
$supportsIQFeed = true;
$requiresSubscription = true;
$moduleName = "<a rel='nofollow' target='_blank' href='https://qnt.co/tastytrade'>tastytrade</a>";
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>