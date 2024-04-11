<p>To send a MOC order, call the <code>MarketOnCloseOrder</code> method with a <code>Symbol</code> and quantity. If you don't have sufficient capital for the order, it is rejected.<br></p>

<div class="section-example-container">
<pre class="csharp">// Buy 100 shares of AAPL at the market open
MarketOnCloseOrder("AAPL", 100);

// Sell 100 shares of AAPL at the market open
MarketOnCloseOrder("AAPL", -100);</pre>
<pre class="python"># Buy 100 shares of AAPL at the market open
self.market_on_close_order("AAPL", 100)

# Sell 100 shares of AAPL at the market open
self.market_on_close_order("AAPL", -100)</pre>
</div>

<p>You can provide a tag and <a href="/docs/v2/writing-algorithms/trading-and-orders/order-properties">order properties</a> to the <code>MarketOnCloseOrder</code> method.</p>
<div class="section-example-container">
<pre class="csharp">MarketOnCloseOrder(symbol, quantity, tag: tag, orderProperties: orderProperties);</pre>
<pre class="python">self.market_on_close_order(symbol, quantity, tag=tag, orderProperties=order_properties)</pre>
</div>

<p><?php echo file_get_contents(DOCS_RESOURCES."/order-types/moc-buffer.html"); ?>

<p>You can also place MOC orders after the market close.</p>