<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");

$brokerageDetails = "
<li>Enter whether the sandbox should be used.
<div class='cli section-example-container'>
<pre>$ lean cloud live deploy \"My Project\" --push --open
Use sandbox? (live, paper): live</pre>
</div>
</li>

<li>Enter your API key, API secret, and passphrase.
<div class='cli section-example-container'>
<pre>$ lean cloud live deploy \"My Project\" --push --open
API key: 6d3ef5ca2d2fa52e4ee55624b0471261
API secret: ****************************************************************************************
Passphrase: ****************
</div>
To create new API credentials, see the <a href='https://pro.coinbase.com/profile/api' target='_blank' rel='nofollow'>API settings page</a> on the Coinbase Pro website.
</li>
";

$getDeployCloudAlgorithmsText("Coinbase Pro", true, $brokerageDetails);
?>
