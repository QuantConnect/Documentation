<?
$brokerageName = "Alpaca";
$dataFeedName = "";
$isBrokerage = true;
$brokerageDetails = "
<li>In the browser window that automatically opens, click <span class='button-name'>Allow</span>.

<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Please open the following URL in your browser to authorize the LEAN CLI.
https://www.quantconnect.com/api/v2/live/auth0/authorize?brokerage=alpaca
Will sleep 5 seconds and retry fetching authorization...
</pre>
</div>
</li>

<li>Enter the environment to use.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Live or Paper environment? (live, paper): live</pre>
</div>
</li>
";

$dataFeedDetails = "
<li>In the browser window that automatically opens, click <span class='button-name'>Allow</span>.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Please open the following URL in your browser to authorize the LEAN CLI.
https://www.quantconnect.com/api/v2/live/auth0/authorize?brokerage=alpaca
Will sleep 5 seconds and retry fetching authorization...
</pre>
</div>
</li>

<li>Enter <a href=\"https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/brokerages/alpaca#02-Account-Types\">your API key and API secret</a>.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Alpaca Api Key: PKEFXE36AR5OEG5K5KNQ
Alpaca Api Secret: ****************************************
</pre>
</div>
</li>";
$supportsIQFeed = true;
$requiresSubscription = true;
$moduleName = "Alpaca";
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>
