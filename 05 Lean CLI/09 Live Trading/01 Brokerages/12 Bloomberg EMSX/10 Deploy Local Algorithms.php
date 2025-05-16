<p>You need to <a href='https://www.bloomberg.com/professional/support/software-updates/' rel='nofollow' target='_blank'>install the Bloomberg Terminal and the BBComm component</a> from the Bloomberg website before you can deploy local algorithms with Terminal Link.</p>

<?
$brokerageName = "Terminal Link";
$dataFeedName = "";
$isBrokerage = true;
$brokerageDetails = "
<li>Enter the environment to use.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Environment (Production, Beta): Production</pre>
</div>
</li>

<li>Enter the host and port of the Bloomberg server.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Server host: 127.0.0.1
Server port: 8194</pre>
</div>
</li>

<li>Enter your EMSX configuration
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
EMSX broker: someValue
EMSX account:</pre>
</div>
</li>

<li>Enter your Open FIGI API key.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Open FIGI API key: </pre>
</div>
</li>
";
$dataFeedDetails = "";
$supportsIQFeed = true;
$requiresSubscription = true;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>
