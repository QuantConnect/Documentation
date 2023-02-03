<?php if ($includeIntro) { ?><p>The following table describes the fill logic of limit orders for each data format and order direction:</p><?php } ?>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Data Format</th>
            <th><code>TickType</code></th>
            <th>Order Direction
            </th><th>Fill Condition</th>
            <th>Fill Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Buy</td>
            <td>quote price &lt; limit price</td>
            <td>min(quote price, limit price)</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Quote</code></td>
            <td>Sell</td>
            <td>quote price &gt; limit price</td>
            <td>max(quote price, limit price)</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Buy</td>
            <td>trade price &lt; limit price</td>
            <td>min(trade price, limit price)</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Sell</td>
            <td>trade price &gt; limit price</td>
            <td>max(trade price, limit price)</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>ask low price &lt; limit price</td>
            <td>min(ask high price, limit price)</td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>bid high price &gt; limit price</td>
            <td>max(bid low price, limit price)</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>low price &lt; limit price</td>
            <td>min(high price, limit price)</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>high price &gt; limit price</td>
            <td>max(low price, limit price)</td>
        </tr>
    </tbody>
</table>

<?
$orderType = "limit";
include(DOCS_RESOURCES."/reality-modeling/trade-fills/fill-with-stale-data.php");
?>