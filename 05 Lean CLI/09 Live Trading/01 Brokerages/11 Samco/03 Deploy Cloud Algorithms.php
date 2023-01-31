<?php
$brokerageDetails = "
<li>Enter your Samco credentials.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Client ID:
Client Password:</pre>
</div>
</li>

<li>Enter your year of birth.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Year of Birth:</pre>
</div>
</li>

<li>Enter the product type.
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
</li>

<li>Enter the trading segment.
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
</li>
";
$brokerageName="Samco";
$isSupported=true;
$supportsCashHoldings=false;
$supportsPositionHoldings=false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>