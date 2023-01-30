<?php
$brokerageDetails = "
<li>Enter your Tradier account ID and access token.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Account id: VA000001
Access token: ****************</pre>
</div>
To get these credentials, see your <a href='https://dash.tradier.com/settings/api' target='_blank' rel='nofollow'>Settings/API Access page</a> on the Tradier website.
</li>

<li>Enter whether the developer sandbox should be used.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Use the developer sandbox? (live, paper): </pre>
</div>
</li>
";
$brokerageName="Tradier";
$isSupported=true;
$supportsCashHoldings=false;
$supportsPositionHoldings=false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>