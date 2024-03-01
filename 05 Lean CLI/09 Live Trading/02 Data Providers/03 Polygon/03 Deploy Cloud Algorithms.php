<?
$brokerageName="QuantConnect Paper Trading";
$isSupported=true;
$brokerageDetails="";
$supportsCashHoldings=true;
$supportsPositionHoldings=true;
$dataProviderName = "Polygon";
$dataProviderDetails = "
    <li>Enter your Polygon API key.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Configure credentials for Polygon

Your Polygon API Key:</pre>
</div>

<p>To get your API key, see the <a rel='nofollow' target='_blank' href='https://polygon.io/dashboard/api-keys'>API Keys page</a> on the Polygon website.</p>
    </li>
";
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>
