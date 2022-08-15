<p>In live trading, LEAN gets your account details to populate the <a href="/docs/v2/writing-algorithms/portfolio/"><code>Portfolio</code></a> object with your account balance and holdings and the <a href="/docs/v2/writing-algorithms/trading-and-orders/order-management/transaction-manager"><code>Transactions</code></a> object with your account open positions. LEAN subscribes to receive data for the securities with holdings and open orders. The smallest resolution of the assets added in <code>Initialize</code> will be used.</p>

<div class="section-example-container">
    <pre class="csharp">AddEquity("SPY", Resolution.Hour);
AddEquity("MSFT", Resolution.Second);</pre>
    <pre class="python">self.AddEquity("SPY", Resolution.Hour)
self.AddEquity("MSFT", Resolution.Second)</pre>
</div>

<p>If your brokerage account holds 100 shares of AAPL at $150.00, LEAN will susbcribe to receive second-resolution data for AAPL since the smallest resolution is <code>Resolution.Second</code> of MSFT.</p>