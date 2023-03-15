<?php 
$orderType = "limit";
include(DOCS_RESOURCES."/reality-modeling/trade-fills/best-effort-tradebar.php"); 
?>

<p>Once the model has a valid best effort <code>TradeBar</code>, it can fill the order. The following table shows the fill condition and fill price of limit orders. The model only fills the order once the fill condition is met.</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Order Direction</th>
            <th>Fill Condition</th>
            <th>Fill Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Buy</td>
            <td>low price &lt; limit price</td>
            <td>min(open price, limit price)</td>
        </tr>
        <tr>
            <td>Sell</td>
            <td>high price &gt; limit price</td>
            <td>max(open price, limit price)</td>
        </tr>
    </tbody>
</table>

<p>The model only fills limit orders when the exchange is open.</p>

<?
$orderType = "limit";
include(DOCS_RESOURCES."/reality-modeling/trade-fills/fill-with-stale-data.php");
?>