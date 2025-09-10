<? 
$csharpOrder = 'SetHoldings("IBM", 0.5, asynchronous: true)';
$pythonOrder = 'self.set_holdings("IBM", 0.5, asynchronous=True)';
include(DOCS_RESOURCES."/trading-and-orders/asynchronous-orders.php"); 
?>

<p>When you set multiple asset targets, you likely want to send asynchronous orders.</p>

<div class="section-example-container">
<pre class="csharp">var targets = new List&lt;PortfolioTarget&gt;() { new("SPY", 0.8m), new("IBM", 0.2m) };
SetHoldings(targets, asynchronous: true);
</pre>
<pre class="python">targets = [PortfolioTarget("SPY", 0.8), PortfolioTarget("IBM", 0.2)]
self.set_holdings(targets, asynchronous=True)</pre>
</div>