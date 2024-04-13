<p>LEAN models buying power and margin calls to ensure your algorithm stays within the margin requirements. The amount of leverage available to you depends on the brokerage you use. To change the amount of leverage you can use for a security, pass a <code>leverage</code> argument to the <code>AddCrypto</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddCrypto("BTCUSD", leverage: 3).Symbol;</pre>
    <pre class="python">self.symbol = self.add_crypto("BTCUSD", leverage=3).symbol</pre>
</div>

<p>For more information about the leverage each brokerage provides, see <a href='/docs/v2/cloud-platform/live-trading/brokerages'>Brokerages</a>.</p>
