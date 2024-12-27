<?
$brokerageName = "Charles Schwab";
$dataFeedName = "";
$isBrokerage = true;
$brokerageDetails = "
<li>In the browser window that automatically opens, click <span class='button-name'>Allow</span>.

<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Please open the following URL in your browser to authorize the LEAN CLI.
https://www.quantconnect.com/api/v2/live/auth0/authorize?brokerage=charlesschwab&projectId=<projectId>
Will sleep 5 seconds and retry fetching authorization...
</pre>
</div>
</li>";
$dataFeedDetails = "
<li>In the browser window that automatically opens, click <span class='button-name'>Allow</span>.

<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Please open the following URL in your browser to authorize the LEAN CLI.
https://www.quantconnect.com/api/v2/live/auth0/authorize?brokerage=charlesschwab&projectId=<projectId>
Will sleep 5 seconds and retry fetching authorization...
</pre>
</div>
</li>";
$supportsIQFeed = true;
$requiresSubscription = true;
$moduleName = "Charles Schwab";
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>