<?
$brokerageName = "tastytrade";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Click <span class=\"button-name\">Authenticate</span>.</li>
<li>On the tastytrade website, and log in. Click <span class=\"button-name\">Allow</span> to grant QuantConnect access to your account information and authorization.</li>
<li>Click on the <span class='field-name'>Account Number</span> field and then click the account that you want to use from the drop-down menu.</li>";
$postDeploy = "";
$dataProviderDetails = "<p>In most cases, we suggest using the <a href='/docs/v2/cloud-platform/datasets'>QuantConnect data provider</a>, the <a href='/docs/v2/cloud-platform/datasets/tastytrade'>tastytrade data provider</a>, or both. The order you set them in the deployment wizard defines their order of precedence in Lean.</p>";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
