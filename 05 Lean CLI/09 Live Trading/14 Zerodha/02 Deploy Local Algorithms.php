<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");


$brokerageDetails = "
<li>Enter the number of the organization that has a subscription for the Zerodha module.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Select the organization with the Samco module subscription:
1) Organization 1
2) Organization 2
3) Organization 3
Enter an option: 1</pre>
</div>
</li>

<li>Enter your <a href='https://kite.trade/' target='_blank'>Kite Connect</a> API key and access token.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
API key: hp9erb9ct0lqaxpm
Access token: ********************</pre>
</div>
</li>

<li>Enter the product type.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Product type (MIS, CNC, NRML):</pre>
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
            <td><code>MIS</code></td>
            <td>Intraday products</td>
        </tr>
        <tr>
            <td><code>CNC</code></td>
            <td>Delivery products</td>
        </tr>
        <tr>
            <td><code>NRML</code></td>
            <td>Carry forward products</td>
        </tr>
    </tbody>
</table>
</li>

<li>Enter the trading segment.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Trading segment (EQUITY, COMMODITY):</pre>
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
            <td><code>EQUITY</code></td>
            <td>For trading Equities on the National Stock Exchange of India (NSE) or the Bombay Stock Exchange (BSE)</td>
        </tr>
        <tr>
            <td><code>COMMODITY</code></td>
            <td>For trading commodities on the Multi Commodity Exchange of India (MCX)</td>
        </tr>
    </tbody>
</table>
</li>
";

$dataFeedDetails = "
<li>Enter whether you have a history API subscription.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Do you have a history API subscription? [y/N]: y</pre>
</div>
</li>
";

$supportsIQFeed = false;

$getDeployLocalAlgorithmsText("Zerodha", $brokerageDetails, $dataFeedDetails, $supportsIQFeed);
?>
