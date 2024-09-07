<p>You need to <a href="/docs/v2/research-environment/indicators/bar-indicators#03-Create-Subscriptions">subscribe to some market data</a> and create an indicator in order to calculate a timeseries of indicator values. In this example, use a 20-period <code>AverageTrueRange</code> indicator.</p>
<div class="section-example-container">
        <pre class="csharp">var atr = new AverageTrueRange(20);</pre>
        <pre class="python">atr = AverageTrueRange(20)</pre>
</div>

<?
$variableName='atr';
$pythonImage='<img class="python docs-image" src="https://cdn.quantconnect.com/i/tu/indicator-bar-py-helper-atr.png" alt="Historical average true range data">';

$setWindowSizeExampleContainer='
    <div class="section-example-container">
        <pre class="csharp">// Set the window.size to the desired timeseries length
atr.Window.Size = 50;
atr.TrueRange.Window.Size = 50;</pre>
        <pre class="python"># Set the window.size to the desired timeseries length
atr.window.size = 50
atr.true_range.window.size = 50</pre>
    </div>';

$dataDisplayStep = '
    <li class="csharp">Display the data.</li>
    <div class="csharp section-example-container">
        <pre class="csharp">foreach (var i in Enumerable.Range(0, 5).Reverse())
{
    Console.WriteLine($"{atr[i].EndTime:yyyyMMdd} {atr[i].Value:f4} {atr.TrueRange[i].Value:f4}");
}</pre>
    </div>
    <img class="csharp docs-image" src="https://cdn.quantconnect.com/i/tu/indicator-bar-cs-classic-atr.png" alt="Historical average true range data">

    <li class="python">Populate a <code>DataFrame</code> with the data in the <code>Indicator</code> object.</li>
    <div class="python section-example-container">
        <pre class="python">atr_dataframe = pd.DataFrame({
    "current": pd.Series({x.end_time: x.value for x in atr}), 
    "truerange": pd.Series({x.end_time: x.value for x in atr.true_range})
}).sort_index()</pre>
    </div>
    <img class="python docs-image" src="https://cdn.quantconnect.com/i/tu/indicator-bar-py-classic-atr.png" alt="Historical average true range data">';
include(DOCS_RESOURCES."/indicators/create-indicator-timeseries.php");
?>