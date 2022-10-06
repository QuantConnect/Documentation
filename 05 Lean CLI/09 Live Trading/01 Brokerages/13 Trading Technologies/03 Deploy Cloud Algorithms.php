<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");

$brokerageDetails = "
<li>Enter your TT user name, session password, account name, app key, app secret, environment, and routing sender.

<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
User name:
Session password: 
Account name: 
REST app key:
REST app secret:
REST environment:
Order routing sender comp id: </div>

<p>Our TT integration routes orders via the TT FIX 4.4 Connection. <a rel='nofollow' target='_blank' href='https://www.tradingtechnologies.com/contact/'>Contact your TT representative</a> to set the exchange where you would like your orders sent. Your account details are not saved on QuantConnect.</p>
</li>
";

$getDeployCloudAlgorithmsText("Trading Technologies", true, $brokerageDetails);
?>
