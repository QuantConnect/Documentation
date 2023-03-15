<?php 
$orderType = "stop market";
include(DOCS_RESOURCES."/reality-modeling/trade-fills/best-effort-tradebar.php"); 
?>

<p>Once the stop condition is met, the model fills the orders and sets the fill price.</p>

<p>Once the model has a valid best effort <code>TradeBar</code>, it can fill the order. The following table shows the stop condition and fill price of stop market orders. The model only fills the order once the stop condition is met.</p>

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
            <td>high price &gt;= stop price</td>
            <td>max(open price, stop price) + slippage</td>
        </tr>
        <tr>
            <td>Sell</td>
            <td>low price &lt;= stop price</td>
            <td>min(open price, stop price) - slippage</td>
        </tr>
    </tbody>
</table>


<p>The model only fills stop market orders during regular trading hours.</p>
<?
$orderType = "stop market";
include(DOCS_RESOURCES."/reality-modeling/trade-fills/fill-with-stale-data.php");
?>
