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
</table>

<li>Click the <span class='field-name'>Data Provider</span> field and then click one of the data feeds from the drop-down menu.</li>
<p>The following table describes the available data feeds:</p>
<table class='qc-table table'>
    <thead>
        <tr>
            <th style='width: 25%'>Data Feed</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>QuantConnect</td>
            <td>Use data collected across all of the exchanges. For more details about this data feed, see <a href='/docs/v2/cloud-platform/live-trading/data-feeds'>Data Feeds</a>.</td>
        </tr>
        <tr>
            <td>Tradier</td>
            <td>Use data sourced directly from Tradier. This data feed isn't available for the demo environment. For more details about this data feed, see the <a href='/docs/v2/cloud-platform/live-trading/data-feeds/brokerage-data-feeds/tradier'>Tradier data feed</a> guide.</td>
        </tr>
    </tbody>
</table>";
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>