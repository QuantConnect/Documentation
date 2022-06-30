<p>A chart series displays data on the chart. To add a series to a chart, create a <code>Series</code> object and then call the <code>AddSeries</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">var series = new Series("&lt;seriesName&gt;");
chart.AddSeries(series);</pre>
    <pre class="python">series = Series("&lt;seriesName&gt;")
chart.AddSeries(series)</pre>
</div>

<h4>Arguments</h4>

<p>There are several other headers for the <code>Series</code> constructor.</p>
<div class="section-example-container">
    <pre>Series(name, type)
Series(name, type, index)
Series(name, type, index, unit)
Series(name, type, unit)
Series(name, type, unit, color)
Series(name, type, unit, color, symbol)
</pre>
</div>

<p>The following table describes the constructor arguments:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Argument</th>
            <th>Data Type</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>name</code></td>
            <td><code class="csharp">string</code><code class="python">str</code></td>
	    <td>Name of the series</td>
        </tr>
        <tr>
            <td><code>type</code></td>
            <td><code>SeriesType</code></td>
	    <td>Type of the series</td>
        </tr>
        <tr>
            <td><code>index</code></td>
            <td><code>int</code></td>
	    <td>Index position on the chart of the series</td>
        </tr>
        <tr>
            <td><code>unit</code></td>
            <td><code class="csharp">string</code><code class="python">str</code></td>
	    <td>Unit for the series axis</td>
        </tr>
        <tr>
            <td><code>color</code></td>
            <td><code>Color</code></td>
	    <td>Color of the series</td>
        </tr>
        <tr>
            <td><code>symbol</code></td>
            <td><code>ScatterMarkerSymbol</code></td>
	    <td>Symbol for the marker in a scatter plot series</td>
        </tr>
    </tbody>
</table>

<p>The default <code>Series</code> is a line chart with a "$" unit on index 0.</p>

<h4>Names</h4>

<p>The <code>Series</code> constructor expects a name argument. If you add a series to one of the default charts, some series names may be reserved. The following table shows the reserved series name for the default charts:</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Chart Name</th>
            <th>Reserved Series Names</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Strategy Equity</td>
	    <td>Equity, Daily Performance</td>
        </tr>
        <tr>
            <td>Capacity</td>
	    <td>Strategy Capacity</td>
        </tr>
        <tr>
            <td>Drawdown</td>
	    <td>Equity Drawdown</td>
        </tr>
        <tr>
            <td>Benchmark</td>
	    <td>Benchmark</td>
        </tr>
        <tr>
            <td>Alpha</td>
	    <td>Direction Score, Magnitude Score</td>
        </tr>
        <tr>
            <td>Alpha Count</td>
	    <td>Count</td>
        </tr>
    </tbody>
</table>

<h4>Types</h4>

<?php echo file_get_contents(DOCS_RESOURCES."/enumerations/series_type.html"); ?>

<h4>Index</h4>

<p>The series index refers to its position in the chart. If all the series are at index 0, they lay on top of each other. If each series has its own index, each series will be separate on the chart. The following image shows an EMA cross chart with both EMA series set to the same index:</p>
<img src="https://cdn.quantconnect.com/i/tu/ema-plot-same-index.png" class="docs-image" alt="Ema values are on the same chart window">

<p>The following image shows the same EMA series, but with the short EMA on index 0 and the long EMA on index 1:</p>
<img src="https://cdn.quantconnect.com/i/tu/ema-plot-separate-indices.png" class="docs-image" alt="Ema values are on separate chart windows">


<h4>Colors</h4>

<p>To view the available <code>Color</code> options, see the <a rel="nofollow" target="_blank" href="https://docs.microsoft.com/en-us/dotnet/api/system.drawing.color?view=net-6.0#properties">Color Struct Properties</a> in the .NET documentation.</p>

<h4>Scatter Marker Symbols</h4>

<?php echo file_get_contents(DOCS_RESOURCES."/enumerations/scatter_marker_symbol.html"); ?>
