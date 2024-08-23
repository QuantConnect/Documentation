<?
$brokerageName = "Alpaca";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Click on the <span class='field-name'>Environment</span> field and then click one of the environments.</li>
<p>The following table shows the supported environments:</p>

<table class='table qc-table'>
    <thead>
        <tr>
            <th>Environment</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Paper</td>
            <td>Trade with paper money</td>
        </tr>
        <tr>
            <td>Live</td>
            <td>Trade with real money</td>
        </tr>
    </tbody>
</table>
<li>Check the <span class=\"box-name\">Authorization</span> check box and then click <span class=\"button-name\">Authenticate</span>.</li>
<li>On the Alpaca website, click <span class=\"button-name\">Allow</span> to grant QuantConnect access to your account information and authorization.</li>";
$dataProviderDetails = "<p>In most cases, we suggest using the <a href='/docs/v2/cloud-platform/datasets'>QuantConnect data provider</a>, the <a href='/docs/v2/cloud-platform/datasets/alpaca'>Alpaca data provider</a>, or both. The order you set them in the deployment wizard defines their order of precedence in Lean.</p><p>If you add the Alpaca data provider, enter your API key and secret. To get your API key and secret, see <a href='/docs/v2/cloud-platform/live-trading/brokerages/alpaca#02-Account-Types'>Account Types</a>. Your account details are not saved on QuantConnect.</p>";
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
