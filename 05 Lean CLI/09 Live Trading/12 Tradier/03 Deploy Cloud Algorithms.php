<?php
include(DOCS_RESOURCES."/brokearges/cli-deployment/deploy-cloud-algorithms.php");

$brokerageDetails = "
<li>Enter your Tradier account id and access token. You can find these credentials on your <a href='https://dash.tradier.com/settings/api' target='_blank'>Settings/API Access</a> page.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Account id: VA000001
Access token: ****************</pre>
</div>
</li>

<li>Enter which environment you want to use. You can either choose <code>demo</code> or <code>real</code>.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Environment (demo, real): demo</pre>
</div>
</li>
";

$getDeployCloudAlgorithmsText("Tradier", true, $brokerageDetails);
?>