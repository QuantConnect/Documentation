<?php 
$providerName = "Numerai";
include(DOCS_RESOURCES."/live-trading/signal-exports/send-portfolio-holdings.php");
?>

<p>To send targets that aren't based on your current portfolio holdings, pass a list of <code>PortfolioTarget</code> objects to the <code>SetTargetPortfolio</code> method. In this situation, the number you pass to the <code>PortfolioTarget</code> constructor represents the portfolio weight. Don't use the <code>PortfolioTarget.Percent</code> method.</p>

<div class="section-example-container">
<pre class="csharp">var success = SignalExport.SetTargetPortfolio(targets);</pre>
<pre class="python">success = self.SignalExport.SetTargetPortfolio(targets)</pre>
</div>
