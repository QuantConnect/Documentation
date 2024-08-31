<p>In live trading, LEAN populates the <a href="/docs/v2/writing-algorithms/portfolio/"><code class="csharp">Portfolio</code><code class="python">portfolio</code></a> object with your account holdings and the <a href="/docs/v2/writing-algorithms/trading-and-orders/order-management/transaction-manager"><code class="csharp">Transactions</code><code class="python">transactions</code></a> object with your open positions. If you don't manually subscribe to the assets in your account, LEAN subscribes to them with the lowest resolution of the subscriptions in your algorithm. For example, say you hold AAPL shares in your account and create the following subscriptions in your algorithm:</p>
<div class="section-example-container">
    <pre class="csharp">// Subscribe to security data at different resolutions to inject security data into algorithm at the chosen resolutions.
AddEquity("SPY", Resolution.Hour);
AddEquity("MSFT", Resolution.Second);</pre>
    <pre class="python"># Subscribe to security data at different resolutions to inject security data into algorithm at the chosen resolutions.
self.add_equity("SPY", Resolution.HOUR)
self.add_equity("MSFT", Resolution.SECOND)</pre>
</div>

<p>In this case, LEAN subscribes to second-resolution data for AAPL since the lowest resolution in your algorithm is <td><code class="csharp">Resolution.Second</code><code class="python">Resolution.SECOND</code></th>.</p>