<p>To add a data point to a chart series, call the <code class="csharp">Plot</code><code class="python">plot</code> method. If you haven't already created a chart and series with the names you pass to the <code class="csharp">Plot</code><code class="python">plot</code> method, the chart and/or series is automatically created.</p>

<div class="section-example-container">
    <pre class="csharp">Plot("&lt;chartName&gt;", "&lt;seriesName&gt;", value);</pre>
    <pre class="python">self.plot("&lt;chartName&gt;", "&lt;seriesName&gt", value)</pre>
</div>

<p>The <code>value</code> argument can be an integer or decimal number. If the chart is a time series, the value is added to the chart using the algorithm time as the x-coordinate.</p>

<?php echo file_get_contents(DOCS_RESOURCES."/plotting/plot-current-indicator-values.html"); ?>
<?php echo file_get_contents(DOCS_RESOURCES."/plotting/plot-all-indicator-values.html"); ?>
