<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");


$brokerageDetails = "
<li>Enter the Atreyu server configuration.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Host:
Request port:
Subscribe port:</pre>
</div>
</li>

<li>Enter your Atreyu credentials.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Username:
Password:
Client id:</pre>
</div>
</li>

<li>Enter the broker MPID to use.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Broker MPID:</pre>
</div>
</li>

<li>Enter the locate rqd to use.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Locate rqd:</pre>
</div>
</li>
";
$dataFeedDetails = "";
$supportsIQFeed = true;
$requiresSubscription = true;

$getDeployLocalAlgorithmsText("Atreyu", $brokerageDetails, $dataFeedDetails, $supportsIQFeed, $requiresSubscription);
?>