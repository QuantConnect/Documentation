<p>After you define the custom data class, in the <code class="csharp">Initialize</code><code class="python">initialize</code> method of your algorithm, call the <code class="csharp">AddData</code><code class="python">add_data</code> method. This method gives LEAN the type factory to create the objects, the name of the data, and the resolution at which to poll the data source for updates.</p>

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
    def initialize(self) -&gt; None:
        self._symbol = self.add_data(MyCustomDataType, "&lt;name&gt;", Resolution.DAILY).symbol</pre>
</div>

<p>The <code>resolution</code> argument should match the resolution of your custom dataset. The lowest reasonable resolution is every minute. Anything more frequent than every minute is very slow to execute. The frequency that LEAN checks the data source depends on the <code>resolution</code> argument. The following table shows the polling frequency of each resolution:</p> 

<?php include(DOCS_RESOURCES."/datasets/live-dataset-polling-frequency-table.html"); ?>

<p>There are several other signatures for the <code class="csharp">AddData</code><code class="python">add_data</code> method.</p>
<div class="python section-example-container">
    <pre>self.add_data(type, ticker, resolution)
self.add_data(type, ticker, resolution, time_zone, fill_forward, leverage)
self.add_data(type, ticker, properties, exchange_hours, resolution, fill_forward, leverage)</pre>
</div>
<div class="csharp section-example-container">
    <pre>AddData&lt;T&gt;(ticker, resolution);
AddData&lt;T&gt;(ticker, resolution, fillForward, leverage);
AddData&lt;T&gt;(ticker, resolution, timeZone, fillForward, leverage);
AddData&lt;T&gt;(ticker, properties, exchangeHours, resolution, fillForward, leverage);</pre>
</div>
