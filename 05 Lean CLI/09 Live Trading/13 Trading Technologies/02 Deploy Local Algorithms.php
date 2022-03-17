<?php
include(DOCS_RESOURCES."/brokearges/cli-deployment/deploy-local-algorithms.php");


$brokerageDetails = "
<li>Enter the number of the organization that has a subscription for the Trading Technologies module.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Select the organization with the Trading Technologies module subscription:
1) Organization 1
2) Organization 2
3) Organization 3
Enter an option: 1</pre>
</div>
</li>

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

$getDeployLocalAlgorithmsText("Trading Technologies", $brokerageDetails, $dataFeedDetails, $supportsIQFeed);
?>