<p>After you define the custom data class, in the <code>Initialize</code> method of your algorithm, call the <code>AddData</code> method. This method gives LEAN the type factory to create the objects, the name of the data, and the resolution at which to poll the data source for updates.</p>

<div class="section-example-container">
    <pre class="csharp">public class MyAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    public override void Initialize()
    {
        _symbol = AddData&lt;MyCustomDataType&gt;("&lt;name&gt;", Resolution.Daily).Symbol;
    }
}</pre>
    <pre class="python">class MyAlgorithm(QCAlgorithm): 
    def Initialize(self) -&gt; None:
        self.symbol = self.AddData(MyCustomDataType, "&lt;name&gt;", Resolution.Daily).Symbol</pre>
</div>

<p>The <code>resolution</code> argument should match the resolution of your custom dataset. The lowest reasonable resolution is every minute. Anything more frequent than every minute is very slow to execute. The frequency that LEAN checks the data source depends on the <code>resolution</code> argument. The following table shows the polling frequency of each resolution:</p> 

<?php include(DOCS_RESOURCES."/datasets/live-dataset-polling-frequency-table.html"); ?>

<p>There are several other signatures for the <code>AddData</code> method.</p>
<div class="python section-example-container">
    <pre>self.AddData(type, ticker, resolution)
self.AddData(type, ticker, resolution, timeZone, fillForward, leverage)
self.AddData(type, ticker, properties, exchangeHours, resolution, fillForward, leverage)</pre>
</div>
<div class="csharp section-example-container">
    <pre>AddData&lt;T&gt;(ticker, resolution);
AddData&lt;T&gt;(ticker, resolution, fillForward, leverage);
AddData&lt;T&gt;(ticker, resolution, timeZone, fillForward, leverage);
AddData&lt;T&gt;(ticker, properties, exchangeHours, resolution, fillForward, leverage);</pre>
</div>
