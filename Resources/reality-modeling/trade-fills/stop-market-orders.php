<?php if ($includeIntro) { ?><p>The following table describes the fill logic of stop market orders for each data format and order direction. Once the stop condition is met, the model fills the orders and sets the fill price.</p><?php } ?>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Data Format</th>
            <th><code>TickType</code></th>
            <th>Order Direction
            </th><th>Stop Condition</th>
            <th>Fill Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Buy</td>
            <td>quote price &gt; stop price</td>
            <td>max(stop price, quote price + slippage)</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Sell</td>
            <td>quote price &lt; stop price</td>
            <td>min(stop price, quote price - slippage)</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Buy</td>
            <td>trade price &gt; stop price</td>
            <td>max(stop price, last trade price + slippage)</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Sell</td>
            <td>trade price &lt; stop price</td>
            <td>min(stop price, last trade price - slippage)</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>ask high price &gt; stop price</td>
            <td>max(stop price, ask close price + slippage)</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>bid low price &lt; stop price</td>
            <td>min(stop price, bid close price - slippage)</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>high price &gt; stop price</td>
            <td>max(stop price, close price + slippage)</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>low price &lt; stop price</td>
            <td>min(stop price, close price - slippage)</td>
        </tr>
    </tbody>
</table>

<p>The model only fills stop market orders when the exchange is open.</p>
<?
$orderType = "stop market";
include(DOCS_RESOURCES."/reality-modeling/trade-fills/fill-with-stale-data.php");
?>