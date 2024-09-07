<p>You need to <a href="/docs/v2/research-environment/indicators/data-point-indicators#03-Create-Subscriptions">subscribe to some market data</a> and create an indicator in order to calculate a timeseries of indicator values. In this example, use a 20-period 2-standard-deviation <code>BollingerBands</code> indicator.</p>
<div class="section-example-container">
        <pre class="csharp">var bb = new BollingerBands(20, 2);</pre>
        <pre class="python">bb = BollingerBands(20, 2)</pre>
</div>

<?
$variableName='bb';
$pythonImage='<img class="python docs-image" src="https://cdn.quantconnect.com/i/tu/indicator-datapoint-py-helper-bb.png" alt="Historical bollinger band data">';

$setWindowSizeExampleContainer='
    <div class="section-example-container">
        <pre class="csharp">// Set the window.size to the desired timeseries length
bb.Window.Size=50;
bb.LowerBand.Window.Size=50;
bb.MiddleBand.Window.Size=50;
bb.UpperBand.Window.Size=50;
bb.BandWidth.Window.Size=50;
bb.PercentB.Window.Size=50;
bb.StandardDeviation.Window.Size=50;
bb.Price.Window.Size=50;</pre>
        <pre class="python"># Set the window.size to the desired timeseries length
bb.window.size=50
bb.lower_band.window.size=50
bb.middle_band.window.size=50
bb.upper_band.window.size=50
bb.band_width.window.size=50
bb.percent_b.window.size=50
bb.standard_deviation.window.size=50
bb.price.window.size=50</pre>
    </div>';

$dataDisplayStep = '
    <li class="csharp">Display the data.</li>
    <div class="csharp section-example-container">
        <pre class="csharp">foreach (var i in Enumerable.Range(0, 5).Reverse())
{
    Console.WriteLine($"{bb[i].EndTime:yyyyMMdd} {bb[i].Value:f4} {bb.LowerBand[i].Value:f4} {bb.MiddleBand[i].Value:f4} {bb.UpperBand[i].Value:f4} {bb.BandWidth[i].Value:f4} {bb.PercentB[i].Value:f4} {bb.StandardDeviation[i].Value:f4} {bb.Price[i].Value:f4}");
}</pre>
    </div>
    <img class="csharp docs-image" src="https://cdn.quantconnect.com/i/tu/indicator-datapoint-cs-classic-bb.png" alt="Historical bollinger band data">

    <li class="python">Populate a <code>DataFrame</code> with the data in the <code>Indicator</code> object.</li>
    <div class="python section-example-container">
        <pre class="python">bb_dataframe = pd.DataFrame({
    "current": pd.Series({x.end_time: x.value for x in bb}),
    "lowerband": pd.Series({x.end_time: x.value for x in bb.lower_band}),
    "middleband": pd.Series({x.end_time: x.value for x in bb.middle_band}),
    "upperband": pd.Series({x.end_time: x.value for x in bb.upper_band}),
    "bandwidth": pd.Series({x.end_time: x.value for x in bb.band_width}),
    "percentb": pd.Series({x.end_time: x.value for x in bb.percent_b}),
    "standarddeviation": pd.Series({x.end_time: x.value for x in bb.standard_deviation}),
    "price": pd.Series({x.end_time: x.value for x in bb.price})
}).sort_index()</pre>
    </div>
    <img class="python docs-image" src="https://cdn.quantconnect.com/i/tu/indicator-datapoint-py-classic-bb.png" alt="Historical bollinger band data"';
include(DOCS_RESOURCES."/indicators/create-indicator-timeseries.php");
?>