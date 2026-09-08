<?
$brokerageDetails = "
<li>Enter your Clear Street access token and account ID.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Access token: ********************************
Account id: 125046</pre>
</div>
" . file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/clear-street.html") . "
</li>";
$dataProviderDetails = "";
$brokerageName="Clear Street";
$isSupported=true;
$supportsCashHoldings=true;
$supportsPositionHoldings=true;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>
