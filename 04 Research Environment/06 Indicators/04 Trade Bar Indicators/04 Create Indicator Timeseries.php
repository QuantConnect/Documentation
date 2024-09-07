<p>You need to <a href="/docs/v2/research-environment/indicators/trade-bar-indicators#03-Create-Subscriptions">subscribe to some market data</a> and create an indicator in order to calculate a timeseries of indicator values. In this example, use a 20-period <code>VolumeWeightedAveragePriceIndicator</code> indicator.</p>
<div class="section-example-container">
        <pre class="csharp">var vwap = new VolumeWeightedAveragePriceIndicator(20);</pre>
        <pre class="python">vwap = VolumeWeightedAveragePriceIndicator(20)</pre>
</div>

<?
$variableName='vwap';
$pythonImage='<img class="python docs-image" src="https://cdn.quantconnect.com/i/tu/indicator-tradebar-py-helper-vwap.png" alt="Historical VWAP data">';

$setWindowSizeExampleContainer='
    <div class="section-example-container">
        <pre class="csharp">// Set the window.size to the desired timeseries length
vwap.Window.Size = 50;</pre>
        <pre class="python"># Set the window.size to the desired timeseries length
vwap.window.size = 50</pre>
    </div>';

$dataDisplayStep = '
    <li class="csharp">Display the data.</li>
    <div class="csharp section-example-container">
        <pre class="csharp">foreach (var i in Enumerable.Range(0, 5).Reverse())
{
    Console.WriteLine($"{vwap[i].EndTime:yyyyMMdd} {vwap[i].Value:f4}");
}</pre>
    </div>
    <img class="csharp docs-image" src="https://cdn.quantconnect.com/i/tu/indicator-tradebar-cs-classic-vwap.png" alt="Historical VWAP data">

    <li class="python">Populate a <code>DataFrame</code> with the data in the <code>Indicator</code> object.</li>
    <div class="python section-example-container">
        <pre class="python">vwap_dataframe = pd.DataFrame({
    "current": pd.Series({x.end_time: x.value for x in vwap}))
}).sort_index()</pre>
    </div>
    <img class="python docs-image" src="https://cdn.quantconnect.com/i/tu/indicator-tradebar-py-classic-vwap.png" alt="Historical VWAP data">';
include(DOCS_RESOURCES."/indicators/create-indicator-timeseries.php");
?>