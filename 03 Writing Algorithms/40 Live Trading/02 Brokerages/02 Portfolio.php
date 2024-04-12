<p>In live trading, LEAN populates the <a href="/docs/v2/writing-algorithms/portfolio/"><code>Portfolio</code></a> object with your account holdings and the <a href="/docs/v2/writing-algorithms/trading-and-orders/order-management/transaction-manager"><code>Transactions</code></a> object with your open positions. If you don't manually subscribe to the assets in your account, LEAN subscribes to them with the lowest resolution of the subscriptions in your algorithm. For example, say you hold AAPL shares in your account and create the following subscriptions in your algorithm:</p>
<div class="section-example-container">
    <pre class="csharp">AddEquity("SPY", Resolution.Hour);
AddEquity("MSFT", Resolution.Second);</pre>
    <pre class="python">self.add_equity("SPY", Resolution.HOUR)
self.add_equity("MSFT", Resolution.SECOND)</pre>
</div>

<p>In this case, LEAN subscribes to second-resolution data for AAPL since the lowest resolution in your algorithm is <code>Resolution.Second</code>.</p>