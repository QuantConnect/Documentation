<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
$brokerageDetails = "
<li>Enter your API key and API secret. You can gather these credentials from the <a href='https://www.kraken.com/u/security/api'>API Management Settings</a> page on the Kraken website.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\"
API key: 
Secret key: </pre>
</div>
</li>

<li>Enter your verification tier. For more information about verification tiers, see <a href='https://support.kraken.com/hc/en-us/articles/360001395743-Verification-levels-explained' target='_blank' rel='nofollow'>Verification levels explained</a> on the Kraken website.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\"
Verification Tier:</pre>
</div>
</li>
";
$getDeployCloudAlgorithmsText("Kraken", true, $brokerageDetails);
?>