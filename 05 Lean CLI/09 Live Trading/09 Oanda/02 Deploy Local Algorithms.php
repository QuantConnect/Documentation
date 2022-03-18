<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");


$brokerageDetails = "
<li>Enter your OANDA account id. You can find this number on your <a href='https://www.oanda.com/account/statement/' target='_blank'>Account Statement page</a>.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Account id: 001-011-5838423-001</pre>
</div>
</li>

<li>Enter your OANDA API token. You can generate a token on the <a href='https://www.oanda.com/account/tpa/personal_token' target='_blank'>Manage API Access page</a>.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
API token: ****************</pre>
</div>
</li>

<li>Enter which environment you want to use. You can either choose <code>trade</code> for fxTrade or <code>practice</code> for fxTrade Practice.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Environment (practice, trade): practice</pre>
</div>
</li>
";

$dataFeedDetails = "";
$supportsIQFeed = true;
$requiresSubscription = false;

$getDeployLocalAlgorithmsText("Oanda", $brokerageDetails, $dataFeedDetails, $supportsIQFeed, $requiresSubscription);
?>