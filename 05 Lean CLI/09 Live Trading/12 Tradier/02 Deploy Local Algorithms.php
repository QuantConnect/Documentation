<?php
include(DOCS_RESOURCES."/brokearges/cli-deployment/deploy-local-algorithms.php");


$brokerageDetails = "
<li>Enter your Tradier account id and access token. You can find these credentials on your <a href='https://dash.tradier.com/settings/api' target='_blank'>Settings/API Access</a> page.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Account id: VA000001
Access token: ****************</pre>
</div>
</li>

<li>Enter whether the developer sandbox should be used.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Use the developer sandbox? [y/N]: y</pre>
</div>
</li>
";
$dataFeedDetails = "";
$supportsIQFeed = true;

$getDeployLocalAlgorithmsText("Tradier", $brokerageDetails, $dataFeedDetails, $supportsIQFeed);
?>