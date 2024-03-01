<?
$brokerageName="QuantConnect Paper Trading";
$isSupported=true;
$brokerageDetails="";
$supportsCashHoldings=true;
$supportsPositionHoldings=true;
$dataFeedName = "IEX Cloud";
$dataFeedDetails = "
    <li>Enter your IEX Cloud API Key. 
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Configure credentials for IEX
Your iexcloud.io API token publishable key: </pre>
</div>
    </li>

    <li>Enter your price plan.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
IEX Cloud Price plan (Launch, Grow, Enterprise): </pre>
</div>
    </li>
";
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>
