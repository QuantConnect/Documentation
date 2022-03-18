<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");


$brokerageDetails = "
<li>Enter your Trading Technologies credentials.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
User name: john
Session password: ****************       
Account name: jane</pre>
</div>
</li>

<li>Enter the REST configuration.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
REST app key: my-rest-app-key
REST app secret: ******************
REST environment: my-environment</pre>
</div>
</li>

<li>Enter the market data configuration.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Market data sender comp id:
Market data target comp id:
Market data host:
Market data port:</pre>
</div>
</li>

<li>Enter the order routing configuration.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Order routing sender comp id:
Order routing target comp id:
Order routing host:
Order routing port:</pre>
</div>
</li>

<li>Enter whether FIX messages must be logged.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Log FIX messages (yes/no): yes</pre>
</div>
</li>";

$dataFeedDetails = "";

$supportsIQFeed = true;
$requiresSubscription = true;

$getDeployLocalAlgorithmsText("Trading Technologies", $brokerageDetails, $dataFeedDetails, $supportsIQFeed, $requiresSubscription);
?>