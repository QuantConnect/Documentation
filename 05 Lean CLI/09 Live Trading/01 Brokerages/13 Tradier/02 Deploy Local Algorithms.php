<?
$brokerageName = "Tradier";
$dataFeedName = "";
$isBrokerage = true;
$brokerageDetails = "
<li>Enter your Tradier account Id and access token.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Account id: VA000001
Access token: ****************</pre>
</div>
To get these credentials, see your <a href='https://dash.tradier.com/settings/api' target='_blank' rel='nofollow'>Settings/API Access page</a> on the Tradier website.
</li>

<li>Enter whether the developer sandbox should be used.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Use the developer sandbox? (live, paper): live</pre>
</div>
</li>
";
$dataFeedDetails = "";
$supportsIQFeed = true;
$requiresSubscription = false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>
