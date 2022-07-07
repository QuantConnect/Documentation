<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
$brokerageDetails = "
<li>Enter the exchange to use.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
FTX Exchange (FTX, FTXUS): FTXUS</pre>
</div>
</li>

<li>Enter your API key, API secret, and account tier.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
API key: 
Secret key: 
Select the Account Tier (Tier1, Tier2, Tier3, Tier4, Tier5, Tier6, VIP1, VIP2, VIP3, MM1, MM2, MM3): </pre>
</div>
To create new API credentials and to check your account tier, see your Profile page on the <a href='https://ftx.com/profile' target='_blank' rel='nofollow'>FTX</a> or <a href='https://ftx.us/profile' target='_blank' rel='nofollow'>FTX US</a> website. If your account tier changes after you deploy the algorithm, stop the algorithm and then redeploy it to correct the account tier.
</li>
";
$getDeployCloudAlgorithmsText("FTX", true, $brokerageDetails);
?>
