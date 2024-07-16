<?
$brokerageName = "Zerodha";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Enter your Kite Connect access token and key.</li>
<p>To get your access token and key, see <a href='/docs/v2/cloud-platform/live-trading/brokerages/zerodha#02-Account-Types'>Account Types</a>. Your account details aren't saved on QuantConnect.</p>
<li>Click the <span class='field-name'>Product Type</span> field and then click one of the following options from the drop-down menu:</li>
<table class='qc-table table'>
<thead>
    <tr>
        <th style='width: 25%'>Product Type</th>
        <th>Description</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td><span class='button-name'>MIS</span></td>
        <td>Intraday products</td>
    </tr>
    <tr>
        <td><span class='button-name'>CNC</span></td>
        <td>Delivery products</td>
    </tr>
    <tr>
        <td><span class='button-name'>NRML</span></td>
        <td>Carry forward products</td>
    </tr>
</tbody>
</table>

<li>Click the <span class='field-name'>Trading Segment</span> field and then click one of the following options from the drop-down menu:</li>
<table class='qc-table table'>
<thead>
    <tr>
        <th style='width: 25%'>Trading Segment</th>
        <th>Description</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td><span class='button-name'>EQUITY</span></td>
        <td>For trading Equities on the National Stock Exchange of India (NSE) or the Bombay Stock Exchange (BSE)</td>
    </tr>
    <tr>
        <td><span class='button-name'>COMMODITY</span></td>
        <td>For trading commodities on the Multi Commodity Exchange of India (MCX)</td>
    </tr>
</tbody>
</table>
<li>Click the <span class='field-name'>History Subscription</span> field and then click <span class='button-name'>Yes</span> or <span class='button-name'>No</span> from the drop-down menu.</li>
<p>Use this field to declare whether you have a history API subscription on your Kite Connect account.</p>";
$omitTemplateDataProviderText = True;
$dataProviderDetails = "<li>In the <span class='page-section-name'>Data Provider</span> section, click <span class='button-name'>Show</span> and then change the QuantConnect data provider to the Zerodha data provider.</li>";
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
