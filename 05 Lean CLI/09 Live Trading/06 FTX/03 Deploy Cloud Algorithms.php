<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
$brokerageDetails = "
<li>Enter the FTX exchange to use.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
FTX Exchange [FTX|FTXUS]:</pre>
</div>
</li>

<li>Enter your API key and API secret. You can create a new API key on your Profile page on the <a href='https://ftx.com/profile' target='_blank' rel='nofollow'>FTX</a> or <a href='https://ftx.us/profile' target='_blank' rel='nofollow'>FTX US</a> website.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
API key: 
Secret key: </pre>
</div>
</li>

<li>Enter your account tier. For example, <code>Tier1</code>. You can get your account tier from your Profile page on the <a href='https://ftx.com/profile' target='_blank'>FTX</a> or <a href='https://ftx.us/profile' target='_blank'>FTX US</a> website. If your account tier changes after you deploy the algorithm, stop the algorithm and then redeploy it to correct the account tier.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Account Tier: </pre>
</div>
</li>
";
$getDeployCloudAlgorithmsText("FTX", true, $brokerageDetails);
?>
