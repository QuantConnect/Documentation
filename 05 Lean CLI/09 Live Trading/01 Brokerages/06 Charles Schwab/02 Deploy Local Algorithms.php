<?
$brokerageName = "Charles Schwab";
$dataFeedName = "";
$isBrokerage = true;
$brokerageDetails = "
<li>If your project is local-only project, LEAN CLI will prompt you to enter the ID of any cloud project of your organization to proceed with authentication, see <a href=https://www.quantconnect.com/docs/v2/cloud-platform/projects/getting-started#13-Get-Project-Id</a>Get Project ID</a> for more information.</li>

<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Please enter any cloud project ID to proceed with Auth0 authentication:
</pre>
</div>
</li>
<li>In the browser window that automatically opens, click <span class='button-name'>Allow</span>.

<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Please enter any cloud project ID to proceed with Auth0 authentication: &lt;projectId&gt;
Please open the following URL in your browser to authorize the LEAN CLI.
https://www.quantconnect.com/api/v2/live/auth0/authorize?brokerage=charlesschwab&projectId=&lt;projectId&gt;
Will sleep 5 seconds and retry fetching authorization...
</pre>
</div>
</li>";
$dataFeedDetails = "";
$supportsIQFeed = true;
$requiresSubscription = true;
$moduleName = "Charles Schwab";
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>