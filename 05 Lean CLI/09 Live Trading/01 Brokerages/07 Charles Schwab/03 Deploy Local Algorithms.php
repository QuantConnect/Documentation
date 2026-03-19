<?
$brokerageName = "Charles Schwab";
$dataFeedName = "";
$isBrokerage = true;
$brokerageDetails = "
<li>Enter your QuantConnect Login ID to proceed with Auth0 authentication.

<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Please enter your Login ID to proceed with Auth0 authentication:
</pre>
</div>
</li>
<li>In the browser window that automatically opens. On the Charles Schwab website, log in, and select your Schwab accounts to link. Click <span class=\"button-name\">Allow</span> to grant QuantConnect access to your account information and authorization.

<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Please enter your Login ID to proceed with Auth0 authentication: &lt;loginId&gt;
Please open the following URL in your browser to authorize the LEAN CLI.
https://www.quantconnect.com/api/v2/live/auth0/authorize?brokerage=charlesschwab&projectId=&lt;projectId&gt;&userId=&lt;loginId&gt;
Will sleep 5 seconds and retry fetching authorization...
</pre>
</div>
</li>

<li>Enter the Charles Schwab account number.

<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
The CharlesSchwab account number (12345678, 23456789): 12345678
</pre>
</div>
</li>";
$dataFeedDetails = "";
$supportsIQFeed = true;
$requiresSubscription = true;
$moduleName = "Charles Schwab";
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>