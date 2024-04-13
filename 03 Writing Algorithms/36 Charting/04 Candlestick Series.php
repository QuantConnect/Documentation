<p>A chart candlestick series displays candlesticks on the chart. To add a candlestick series to a chart, create a <code>CandlestickSeries</code> object and then call the <code>AddSeries</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">var candlestickSeries = new CandlestickSeries("&lt;seriesName&gt;");
chart.AddSeries(candlestickSeries);</pre>
    <pre class="python">candlestick_series = CandlestickSeries("&lt;seriesName&gt;")
chart.add_series(candlestick_series)</pre>
</div>

<p>There are several other headers for the <code>CandlestickSeries</code> constructor.</p>
<div class="section-example-container">
    <pre>CandlestickSeries(name)
CandlestickSeries(name, index)
CandlestickSeries(name, index, unit)
CandlestickSeries(name, unit)
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
            <td><code>index</code></td>
            <td><code>int</code></td>
	        <td>Index position on the chart of the series</td>
        </tr>
        <tr>
            <td><code>unit</code></td>
            <td><code class="csharp">string</code><code class="python">str</code></td>
	        <td>Unit for the series axis</td>
        </tr>
    </tbody>
</table>

<p>The default <code>CandlestickSeries</code> has 0 index and "$" unit.</p>
