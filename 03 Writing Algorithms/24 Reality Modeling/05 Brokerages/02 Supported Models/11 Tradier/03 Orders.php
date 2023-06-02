<p>The <code>TradierBrokerageModel</code> supports several order types, order properties, and most order updates.</p>

<? include(DOCS_RESOURCES."/brokerages/tradier/orders.php"); ?>

<p>The <code>TradierBrokerageModel</code> validates your orders for the following errors before sending them to Tradier:</p>

<table class="qc-table table" id='backtesting-nodes-table'>
    <thead>
        <tr>
            <th>Error</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>ShortOrderIsGtc</code></td>
            <td>You can't short sell with the <code>GoodTilCanceled</code> <a href='/docs/v2/writing-algorithms/trading-and-orders/order-properties#03-Time-In-Force'>time in force</a></td>
        </tr>
        <tr>
            <td><code>SellShortOrderLastPriceBelow5</code></td>
            <td>You can't short sell stock priced below $5</td>
        </tr>
        <tr>
            <td><code>IncorrectOrderQuantity</code></td>
            <td>The order quantity must be between 1 and 10,000,000 shares</td>
        </tr>
    </tbody>
</table>
