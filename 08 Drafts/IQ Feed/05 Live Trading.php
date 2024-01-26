<?
$brokerageName = "QuantConnect Paper Trading";
$dataFeedName = "IQ Feed";
$isBrokerage = false;

$brokerageDetails = "";

$dataFeedDetails = "
    <li>Enter the path to the IQConnect binary. 
    <p>The default path is <span class='public-file-name'>C: / Program Files (x86) / DTN / IQFeed / iqconnect.exe</span> if you used the default settings when installing the IQFeed client.</p>
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
IQConnect binary location [C:/Program Files (x86)/DTN/IQFeed/iqconnect.exe]:</pre>
</div>
    </li>

    <li>Enter your IQFeed username and password.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Username: 123456
Password: **********</pre>
</div>
    </li>

    <li>If you have an IQFeed developer account, enter the product Id and version of your account.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Product id: &lt;yourID&gt;
Product version: 1.0</pre>
</div>
    </li>

    <li>If you don't have an IQFeed developer account, open <span class='public-file-name'>iqlink.exe</span>, log in to IQLink with your username and password, and then enter random numbers for the product id and version.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Product id: 123
Product version: 1.0</pre>
</div>
    </li>
";
$supportsIQFeed = true;
$requiresSubscription = false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>
