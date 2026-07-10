<?
$brokerageDetails = "
<li>Enter your Public secret key and account number.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Secret key: ********************************
Account number: 5PY12345</pre>
</div>
" . file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/public.html") . "
</li>";
$dataProviderDetails = "";
$brokerageName="Public";
$isSupported=true;
$supportsCashHoldings=true;
$supportsPositionHoldings=true;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>
