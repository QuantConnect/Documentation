<?php 
$providerName = "CrunchDAO";
include(DOCS_RESOURCES."/live-trading/signal-exports/send-portfolio-holdings.php");
?>

<p>To send targets that aren't based on your current portfolio holdings, pass a <code>PortfolioTarget</code> object or <span class='csharp'>an array</span><span class='python'>a list</span> of <code>PortfolioTarget</code> objects to the <code class="csharp">SetTargetPortfolio</code><code class="python">set_target_portfolio</code> method. In this situation, the number you pass to the <code>PortfolioTarget</code> constructor represents the portfolio weight. Don't use the <code class="csharp">PortfolioTarget.Percent</code><code class="python">PortfolioTarget.percent</code> method.</p>

<div class="section-example-container">
<pre class="csharp">var target = new PortfolioTarget(_symbol, weight);
var success = SignalExport.SetTargetPortfolio(target);</pre>
<pre class="python">target = PortfolioTarget(self._symbol, weight)
success = self.signal_export.set_target_portfolio(target)</pre>
</div>
