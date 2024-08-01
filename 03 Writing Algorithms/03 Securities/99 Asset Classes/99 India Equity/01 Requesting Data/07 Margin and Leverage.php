<p>LEAN models buying power and margin calls to ensure your algorithm stays within the margin requirements. The amount of margin that's available depends on the <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages/key-concepts'>brokerage model</a> you use. For more information about the margin requirements of each brokerage, see the <span class='page-section-name'>Margin</span> section of the <a href='/docs/v2/cloud-platform/live-trading/brokerages'>brokerage guides</a>. To change the amount of leverage you can use for a security, pass a <code>leverage</code> argument to the <code class="csharp">AddEquity</code><code class="python">add_equity</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddEquity("YESBANK", market: Market.India, leverage: 3).Symbol;</pre>
    <pre class="python">self._symbol = self.add_equity("YESBANK", market=Market.INDIA, leverage=3).symbol</pre>
</div>
