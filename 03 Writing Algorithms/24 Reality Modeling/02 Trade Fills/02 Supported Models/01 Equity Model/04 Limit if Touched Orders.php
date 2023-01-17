<p>The model converts a limit if touched order to a limit order when the trigger condition is met. The following table describes the trigger condition of limit if touched orders for each data format and order direction:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Data Format</th>
            <th><code>TickType</code></th>
            <th>Order Direction</th>
            <th>Trigger Condition</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Buy</td>
            <td>Trade price &lt;= trigger price<br></td>
        </tr>
        <tr>
            <td><code>Tick</code></td>
            <td><code>Trade</code></td>
            <td>Sell</td>
            <td>Trade price &gt;= trigger price</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Buy</td>
            <td>Low price &lt;= trigger price</td>
        </tr>
        <tr>
            <td><code>TradeBar</code></td>
            <td><br></td>
            <td>Sell</td>
            <td>High price &gt;= trigger price</td>
        </tr>
    </tbody>
</table>


<p>Once the limit if touched order triggers, to fill the order, the model checks the fill condition. The following table describes the fill condition and price of each order direction.</p>

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

<p>The model won't trigger or fill limit if touched orders with <a href='/docs/v2/writing-algorithms/reality-modeling/trade-fills/key-concepts#06-Stale-Fills'>stale data</a>.</p>