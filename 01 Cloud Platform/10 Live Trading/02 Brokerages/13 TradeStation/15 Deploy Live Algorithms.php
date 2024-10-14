<?
$brokerageName = "TradeStation";
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
            <td>Simulator</td>
            <td>Trade with paper money</td>
        </tr>
        <tr>
            <td>Live</td>
            <td>Trade with real money</td>
        </tr>
    </tbody>
</table>
<li>Click on <span class=\"button-name\">Authenticate</span> button.</li>
<li>On the TradeStation website, login to your account to grant QuantConnect access to your account information and authorization.</li>
<li>Click on the <span class='field-name'>Select Account Id</span> field and then click on one of your accounts.</li>";
$dataProviderDetails = "<p>In most cases, we suggest using the <a href='/docs/v2/cloud-platform/datasets'>QuantConnect data provider</a>, the <a href='/docs/v2/cloud-platform/datasets/tradestation'>TradeStation data provider</a>, or both. The order you set them in the deployment wizard defines their order of precedence in Lean.</p>";
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>