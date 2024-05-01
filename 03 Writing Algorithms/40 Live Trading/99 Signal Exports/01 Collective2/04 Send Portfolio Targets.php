<p>To send targets to Collective2, pass a <code>PortfolioTarget</code> object or <span class='csharp'>an array</span><span class='python'>a list</span> of <code>PortfolioTarget</code> objects to the <code class="csharp">SetTargetPortfolio</code><code class="python">set_target_portfolio</code> method. The method returns a boolean that represents if the targets were successfully sent to Collective2. In this situation, the number you pass to the <code>PortfolioTarget</code> constructor represents the portfolio weight. Don't use the <code class="csharp">PortfolioTarget.Percent</code><code class="python">PortfolioTarget.percent</code> method.</p>

<div class="section-example-container">
<pre class="csharp">var target = new PortfolioTarget(_symbol, weight);
var success = SignalExport.SetTargetPortfolio(target);</pre>
<pre class="python">target = PortfolioTarget(self._symbol, weight)
success = self.signal_export.set_target_portfolio(target)</pre>
</div>

<p>If you use a margin account, you can send your current portfolio holdings by calling the <code class="csharp">SetTargetPortfolioFromPortfolio</code><code class="python">set_target_portfolio_from_portfolio</code> method.</p>

<div class="section-example-container">
<pre class="csharp">var success = SignalExport.SetTargetPortfolioFromPortfolio();</pre>
<pre class="python">success = self.signal_export.set_target_portfolio_from_portfolio()</pre>
</div>
