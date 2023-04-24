<p>To send targets to Collective2, pass a <code>PortfolioTarget</code> object or a list of <code>PortfolioTarget</code> objects to the <code>SetTargetPortfolio</code> method. The method returns a boolean that represents if the targets were sucessfully sent to the signal export provider. In this situation, the number you pass to the <code>PortfolioTarget</code> constructor represents the portfolio weight. Don't use the <code>PortfolioTarget.Percent</code> method.</p>

<div class="section-example-container">
<pre class="csharp">var target = new PortfolioTarget(_symbol, weight);
var success = SignalExport.SetTargetPortfolio(target);</pre>
<pre class="python">target = PortfolioTarget(self.symbol, weight)
success = self.SignalExport.SetTargetPortfolio(target)</pre>
</div>

<p>If you use a margin account, you can send your current portfolio holdings by calling the <code>SetTargetPortfolioFromPortfolio</code> method.</p>

<div class="section-example-container">
<pre class="csharp">var success = SignalExport.SetTargetPortfolioFromPortfolio();</pre>
<pre class="python">success = self.SignalExport.SetTargetPortfolioFromPortfolio()</pre>
</div>
