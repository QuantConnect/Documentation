<?
$brokerageName="QuantConnect Paper Trading";
$isSupported=true;
$brokerageDetails="";
$supportsCashHoldings=true;
$supportsPositionHoldings=true;
$dataProviderName = "Databento";
$dataProviderDetails = "
    <li>Enter your Databento API key.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Your Databento.com API Key:</pre>
</div>

<p>To get your API key, see the <a rel='nofollow' target='_blank' href='https://databento.com/portal/keys'>API Keys page</a> on the Databento website.</p>
    </li>
";
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>
