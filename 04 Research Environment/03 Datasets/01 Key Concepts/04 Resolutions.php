<? include(DOCS_RESOURCES."/datasets/resolutions.php"); ?>

<p>When you request historical data, the <code>History</code> method uses the resolution of your security subscription. To get historical data with a different resolution, pass a <code>resolution</code> argument to the <code>History</code> method.</p>

<div class="section-example-container">
    <pre class="python">history = qb.history(spy, 10, Resolution.MINUTE)</pre>
    <pre class="csharp">var history = qb.history(spy, 10, Resolution.Minute);</pre>
</div>