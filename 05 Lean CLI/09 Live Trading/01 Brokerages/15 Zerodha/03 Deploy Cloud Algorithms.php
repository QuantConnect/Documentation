<?
$brokerageDetails = "
<li>Enter your <a href='https://kite.trade/' target='_blank'>Kite Connect</a> API key and access token.</li>
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
API key: hp9erb9ct0lqaxpm
Access token: ********************</pre>
</div>


<li>Enter the product type.</li>
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Product type (mis, cnc, nrml):</pre>
</div>
<p>The following table describes the product types:</p>
<table class='qc-table table'>
    <thead>
        <tr>
            <th style='width: 25%'>Product Type</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>mis</code></td>
            <td>Intraday products</td>
        </tr>
        <tr>
            <td><code>cnc</code></td>
            <td>Delivery products</td>
        </tr>
        <tr>
            <td><code>nrml</code></td>
            <td>Carry forward products</td>
        </tr>
    </tbody>
</table>


<li>Enter the trading segment.</li>
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Trading segment (equity, commodity):</pre>
</div>
<p>The following table describes when to use each trading segment:</p>
<table class='qc-table table'>
    <thead>
        <tr>
            <th style='width: 25%'>Trading Segment</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>equity</code></td>
            <td>For trading Equities on the National Stock Exchange of India (NSE) or the Bombay Stock Exchange (BSE)</td>
        </tr>
        <tr>
            <td><code>commodity</code></td>
            <td>For trading commodities on the Multi Commodity Exchange of India (MCX)</td>
        </tr>
    </tbody>
</table>

<li>Enter whether you have a history API subscription.</li>
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Do you have a history API subscription? (true, false): true</pre>
</div>
";
$brokerageName="Zerodha";
$isSupported=true;
$supportsCashHoldings=false;
$supportsPositionHoldings=false;
$useBrokerageDataProvider = true;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>
