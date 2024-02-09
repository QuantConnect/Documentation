<?
$brokerageName = "Samco";
$cashState = false;
$holdingsState = false;
$secondBullet = "";
$authentication = "<li>Enter your client ID, password, and date of birth.</li>
<p>Your account details aren't saved on QuantConnect.</p>
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

<!--
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
-->";
$postDeploy = "";
include(DOCS_RESOURCES."/live-trading/deploy-live-algorithm.php");
?>
