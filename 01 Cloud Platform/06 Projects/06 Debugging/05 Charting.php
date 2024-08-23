<p>You can use the IDE charting capabilities to plot values over time when debugging. To add data points to a custom chart, call the <code class="csharp">Plot</code><code class="python">plot</code> method with a chart name, series name, and value. For a full example, see <a href='/docs/v2/writing-algorithms/charting'>Charting</a>.</p>

<div class='section-example-container'>
    <pre class='csharp'>// Add data points to custom chart 
Plot("Chart Name", "Series Name", value);</pre>
    <pre class='python'># Add data points to custom chart 
self.plot("Chart Name", "Series Name", value)</pre>
</div>

<p><?=$cloudPlatform ? "If you run your algorithm in QuantConnect Cloud, we limit" : "We limit" ?> the number of points a chart can have to 4,000 because intensive charting generates hundreds of megabytes (200MB) of data, which is too much to stream online or display in a web browser. If you exceed the limit, the following error message is thrown:</p>
<span class='error-messages'>Exceeded maximum data points per series, chart update skipped.</span>

