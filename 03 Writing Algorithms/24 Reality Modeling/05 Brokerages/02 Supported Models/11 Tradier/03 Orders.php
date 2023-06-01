<p>The <code>TradierBrokerageModel</code> supports several order types, order properties, and most order updates.</p>

<? include(DOCS_RESOURCES."/brokerages/tradier/orders.php"); ?>

<p>The <code>TradierBrokerageModel</code> validates orders before submission for the following cases.</p>

<ol>
    <li><code>ShortOrderIsGtc</code> - You cannot place short stock orders with <code>GoodTilCanceled</code></li>
    <li><code>SellShortOrderLastPriceBelow5</code> - Sell Short order cannot be placed for stock priced below $5</li>
    <li><code>IncorrectOrderQuantity</code> - Quantity should be between 1 and 10,000,000 shares</li>
</ol>