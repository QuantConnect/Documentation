<? 
$orderType = "trailing stop";
include(DOCS_RESOURCES."/reality-modeling/trade-fills/best-effort-tradebar.php"); 
?>

<p>Once the stop condition is met, the model fills the orders and sets the fill price.</p>

<p>Once the model has a valid best effort <code>TradeBar</code>, it can fill the order. The following table shows the stop condition and fill price of trailing stop orders. The model only fills the order once the stop condition is met.</p>

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

<p>While the stop condition is not met, the model updates the stop price under certain conditions. The following table shows the update condition and stop price value for the nominal trailing amount.</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Order Direction</th>
            <th>Update Condition</th>
            <th>Stop Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Buy</td>
            <td>stop price - low price &lt;= trailing amount</td>
            <td>low price + trailing amount</td>
        </tr>
        <tr>
            <td>Sell</td>
            <td>high price - stop price &lt;= trailing amount</td>
            <td>high price - trailing amount</td>
        </tr>
    </tbody>
</table>

<p>The following table shows the update condition and stop price value for the percentage trailing amount.</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Order Direction</th>
            <th>Update Condition</th>
            <th>Stop Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Buy</td>
            <td>stop price - low price &lt;= low price * trailing amount</td>
            <td>low price *  (1 + trailing amount)</td>
        </tr>
        <tr>
            <td>Sell</td>
            <td>high price - stop price &lt;= high price * trailing amount</td>
            <td>high price * (1 - trailing amount)</td>
        </tr>
    </tbody>
</table>

<p>The model only fills trailing stop orders during regular trading hours.</p>
<?
$orderType = "trailing stop";
include(DOCS_RESOURCES."/reality-modeling/trade-fills/fill-with-stale-data.php");
?>