<?php 
$orderType = "limit if touched";
include(DOCS_RESOURCES."/reality-modeling/trade-fills/best-effort-tradebar.php"); 
?>

<p>After the model has a valid best effort <code>TradeBar</code>, it can check if the trigger price has been touched. The following table describes the trigger condition of limit if touched orders for each order direction:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Order Direction</th>
            <th>Trigger Condition</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Buy</td>
            <td>low price &lt;= trigger price</td>
        </tr>
        <tr>
            <td>Sell</td>
            <td>high price &gt;= trigger price</td>
        </tr>
    </tbody>
</table>

<p>Once the limit if touched order triggers, the model starts to check if it should fill the order. The following table shows the fill condition and fill price of limit if touched orders. The model only fills the order once the fill condition is met</p>

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
            <td>Best effort ask price &lt;= limit price<br></td>
            <td>min(best effort ask price, limit price)</td>
        </tr>
        <tr>
            <td>Sell</td>
            <td>Best effort bid price &gt;= limit price<br></td>
            <td>max(best effort bid price, limit price)</td>
        </tr>
    </tbody>
</table>

<?php echo file_get_contents(DOCS_RESOURCES."/reality-modeling/trade-fills/best-effort-prices.html"); ?>

<p>The model only fills limit orders when the exchange is open.</p>

<p>The model won't trigger or fill limit if touched orders with <a href='/docs/v2/writing-algorithms/reality-modeling/trade-fills/key-concepts#06-Stale-Fills'>stale data</a>.</p>