<?
$brokerageName = "Tradier";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Enter your Tradier account Id and token.</li>";
$authentication .= file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/tradier.html");
$authentication .= "    <li>Click the <span class='field-name'>Environment</span> field and then click one of the environments from the drop-down menu.</li>

<p>The following table shows the supported environments:</p>

<table class='table qc-table'>
    <thead>
        <tr>
            <th style='width: 25%'>Environment</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Real</td>
            <td>Trade with real money</td>
        </tr>
        <tr>
            <td>Demo</td>
            <td>Trade with paper money</td>
        </tr>
    </tbody>
</table>";
$dataProviderDetails = "<p>In most cases, we suggest using the <a href='/docs/v2/cloud-platform/datasets'>QuantConnect data provider</a>, the <a href='/docs/v2/cloud-platform/datasets/tradier'>Tradier data provider</a>, or both. The order you set them in the deployment wizard defines their order of precedence in Lean.</p>";
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
