<p>To send targets, pass a <code>PortfolioTarget</code> object or a list of <code>PortfolioTarget</code> objects to the <code>SetTargetPortfolio</code> method. In this situation, the number you pass to the <code>PortfolioTarget</code> constructor represents the portfolio weight. Don't use the <code>PortfolioTarget.Percent</code> method.</p>

<div class="section-example-container">
<pre class="csharp">var target = new PortfolioTarget(_symbol, quantity);
SignalExport.SetTargetPortfolioFromPortfolio(target);</pre>
<pre class="python">target = PortfolioTarget(self.symbol, quantity)
self.SignalExport.SetTargetPortfolioFromPortfolio(target)</pre>
</div>