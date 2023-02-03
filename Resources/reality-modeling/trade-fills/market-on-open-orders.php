<p>The following table describes the fill price of market on open orders for each data format and order side:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Data Format</th>
            <th><code>TickType</code></th>
            <th>Order Direction</th>
            <th>Fill Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Buy</td>
            <td>quote price + slippage</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Sell</td>
            <td>quote price - slippage</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Buy</td>
            <td>trade price + slippage</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Sell</td>
            <td>trade price - slippage</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>ask open price + slippage</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>bid open price - slippage</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>open price + slippage</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>open price - slippage</td>
        </tr>
    </tbody>
</table>

<p>The model won't fill market on open orders during pre-market hours.</p>
<?
$orderType = "market on open";
include(DOCS_RESOURCES."/reality-modeling/trade-fills/fill-with-stale-data.php");
?>