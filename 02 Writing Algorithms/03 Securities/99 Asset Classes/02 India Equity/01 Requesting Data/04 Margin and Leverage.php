<p>LEAN models buying power and margin calls to ensure your algorithm stays within the margin requirements. The amount of margin that's available depends on the <a href='/docs/v2/writing-algorithms/reality-modeling/brokerages'>brokerage model</a> you use. For more information about the margin requirements of each brokerage, see the <span class='page-section-name'>Margin and Leverage</span> section of the <a href='/docs/v2/our-platform/live-trading/brokerages'>brokerage guides</a>. To change the amount of leverage you can use for a security, pass a <code>leverage</code> argument to the <code>AddEquity</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddEquity("YESBANK", market: Market.India, leverage: 3).Symbol;</pre>
    <pre class="python">self.symbol = self.AddEquity("YESBANK", market=Market.India, leverage=3).Symbol</pre>
</div>