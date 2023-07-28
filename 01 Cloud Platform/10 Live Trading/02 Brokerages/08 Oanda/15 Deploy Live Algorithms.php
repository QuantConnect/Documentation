<?
$brokerageName = "Oanda";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Enter your Oanda account Id and access token.</li>";
$authentication .= file_get_contents(DOCS_RESOURCES."/brokerages/create-credentials/oanda.html");
$authentication .= "<li>Click the <span class='field-name'>Environment</span> field and then click one of the environments.</li>

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
            <td>Real</td>
            <td>Trade real money with fxTrade</td>
        </tr>
        <tr>
            <td>Demo</td>
            <td>Trade paper money with fxTrade Practice</td>
        </tr>
    </tbody>
</table>";
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>