<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");

$brokerageName = "QuantConnect Paper Trading";
$dataFeedName = "IQ Feed";
$isBrokerage = false;

$brokerageDetails = "";

$dataFeedDetails = "
    <li>Enter the path to the IQConnect binary. The default path is <span class='private-file-name'>C:/Program Files (x86)/DTN/IQFeed/iqconnect.exe</span> if you used the default settings when installing the IQFeed client.
<div class='cli section-example-container'>
<pre>$ lean live deploy \"My Project\"
IQConnect binary location [C:/Program Files (x86)/DTN/IQFeed/iqconnect.exe]:</pre>
</div>
    </li>

    <li>Enter your IQFeed username and password.
<div class='cli section-example-container'>
<pre>$ lean live deploy \"My Project\"
Username: 123456
Password: **********</pre>
</div>
    </li>

    <li>Enter the product id and version of your IQFeed developer account.
<div class='cli section-example-container'>
<pre>$ lean live deploy \"My Project\"
Product id: &lt;yourID&gt;
Product version: 1.0</pre>
</div>
    </li>
";

$supportsIQFeed = true;
$requiresSubscription = false;

$getDeployLocalAlgorithmsText($brokerageName, $dataFeedName, $isBrokerage, $brokerageDetails, $dataFeedDetails, $supportsIQFeed, $requiresSubscription);
?>
