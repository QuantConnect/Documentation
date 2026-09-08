<?
$brokerageDetails = "
<li>Enter your Webull App Key, App Secret, account ID, and whether your application has two-factor authentication (2FA) enabled.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
App key: 0a1b2c3d4e5f6a7b8c9d0e1f2a3b4c5d
App secret: *******************************
Account id: 12345678
Use 2FA (yes/no): no</pre>
</div>
" . file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/webull.html") . "
</li>";
$dataProviderDetails = "";
$brokerageName="Webull";
$isSupported=true;
$supportsCashHoldings=true;
$supportsPositionHoldings=true;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>
