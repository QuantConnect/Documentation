<p>To add a sample of open, high, low, and close values to a candlestick series, call the <code>Plot</code> method with the data points. If you haven't already created a chart and series with the names you pass to the <code>Plot</code> method, the chart and/or series is automatically created.</p>

<div class="section-example-container">
    <pre class="csharp">Plot("&lt;chartName&gt;", "&lt;seriesName&gt;", open, high, low, close);</pre>
    <pre class="python">self.Plot("&lt;chartName&gt;", "&lt;seriesName&gt", open, high, low, close)</pre>
</div>

<p>The <code>open</code>, <code>high</code>, <code>low</code>, and <code>close</code> arguments can be an integer for decimal number. If the chart is a time series, the values are added to the chart using the algorithm time as the x-coordinate.</p>

<? include(DOCS_RESOURCES."/plotting/plot-current-trade-bar.html"); ?>
<? include(DOCS_RESOURCES."/plotting/plot-consolidated-values.html"); ?>
