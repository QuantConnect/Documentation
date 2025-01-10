<?
$brokerageName = "Charles Schwab";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Check the <span class=\"box-name\">Authorization</span> check box and then click <span class=\"button-name\">Authenticate</span>.</li>
<li>On the Charles Schwab website, log in, and select your Schwab accounts to link. Click <span class=\"button-name\">Allow</span> to grant QuantConnect access to your account information and authorization.</li>
<li>Click on the <span class='field-name'>Select Account Id</span> field and then click on one of your accounts.</li>";
$postDeploy = "";
$dataProviderDetails = "<p>In most cases, we suggest using the <a href='/docs/v2/cloud-platform/datasets'>QuantConnect data provider</a>, the <a href='/docs/v2/cloud-platform/datasets/charles-schwab'>Charles Schwab data provider</a>, or both. The order you set them in the deployment wizard defines their order of precedence in Lean.</p>";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>