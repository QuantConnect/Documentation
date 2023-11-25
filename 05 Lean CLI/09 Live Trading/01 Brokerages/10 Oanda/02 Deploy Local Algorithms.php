<?
$brokerageName = "Oanda";
$dataFeedName = "";
$isBrokerage = true;
$brokerageDetails = "
<li>Enter the environment to use. Enter <code>Trade</code> for fxTrade or <code>Practice</code> for fxTrade Practice.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Environment? (Practice, Trade): Trade</pre>
</div>
</li>

<li>Enter your OANDA account ID. 
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Account id: 001-011-5838423-001</pre>
</div>
To get your account ID, see your <a href='https://www.oanda.com/account/statement/' target='_blank'>Account Statement page</a> on the OANDA website.
</li>

<li>Enter your OANDA API token.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
API token: ****************</pre>
</div>
To create a token, see the <a href='https://www.oanda.com/account/tpa/personal_token' target='_blank'>Manage API Access page</a> on the OANDA website.
</li>
";

$dataFeedDetails = "";
$supportsIQFeed = true;
$requiresSubscription = false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>
