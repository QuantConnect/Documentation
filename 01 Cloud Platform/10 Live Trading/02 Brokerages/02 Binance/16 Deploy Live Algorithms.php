<?
$brokerageName = "Binance Exchange";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Enter your API key and secret.</li>
<p>To generate your API credentials, see <a href='/docs/v2/cloud-platform/live-trading/brokerages/binance#02-Account-Types'>Account Types</a>. Your account details are not saved on QuantConnect.</p>
<li>Click on the <span class='field-name'>Environment</span> field and then click one of the environments.</li>
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
            <td>Trade with real money</td>
        </tr>
        <tr>
            <td>Demo</td>
            <td>Trade with paper money through the Binance Global brokerage<br></td>
        </tr>
    </tbody>
</table>";
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>