<?php
include(DOCS_RESOURCES."/brokearges/cli-deployment/deploy-local-algorithms.php");


$brokerageDetails = "
<li>Enter the number of the organization that has a subscription for the Atreyu module.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Select the organization with the Atreyu module subscription:
1) Organization 1
2) Organization 2
3) Organization 3
Enter an option: 1</pre>
</div>
</li>

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

$getDeployLocalAlgorithmsText("Atreyu", $brokerageDetails, $dataFeedDetails, $supportsIQFeed);
?>