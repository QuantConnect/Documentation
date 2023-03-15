<p>The following table describes the fill price of market on close orders for each data format and order direction:<br></p>


<table class="qc-table table">
    <thead>
        <tr>
            <th>Data Format</th>
            <th>Order Direction</th>
            <th>Fill Price<br></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>Tick</code></td>
            <td>Buy</td>
            <td>If the model receives the <a href='/docs/v2/cloud-platform/datasets/misconceptions#06-Opening-and-Closing-Auctions'>official closing auction price</a> within one minute after the close, the order fills at official close price + slippage. After one minute, the order fills at the most recent trade price + slippage. If the security doesn't trade within the first two minutes, the order fills at the best effort ask price + slippage.</td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td>Sell</td>
            <td>If the model receives the <a href='/docs/v2/cloud-platform/datasets/misconceptions#06-Opening-and-Closing-Auctions'>official closing auction price</a> within one minute after the close, the order fills at the official close price - slippage. After one minute, the order fills at the most recent trade price - slippage. If the security doesn't trade within the first two minutes after the close, the order fills at the best effort bid price - slippage.</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td>Buy</td>
            <td>Open price + slippage<br></td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td>Sell</td>
            <td>Open price - slippage<br></td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td>Buy</td>
            <td>Best effort ask price + slippage<br></td>
        </tr>
        <tr>
            <td><code>QuoteBar</code></td>
            <td>Sell</td>
            <td>Best effort bid price - slippage<br></td>
        </tr>
    </tbody>
</table>


<p>The model checks the data format in the following order:</p>

<ol>
    <li><code>Tick</code></li>
    <li><code>TradeBar</code></li>
    <li><code>QuoteBar</code></li>
</ol>

<?php echo file_get_contents(DOCS_RESOURCES."/reality-modeling/trade-fills/best-effort-prices.html"); ?>