<p>To receive your custom data in the <code>OnData</code> method, after you define the custom data class, call 

<span class="csharp"><code>AddData&lt;T&gt;(string ticker, Resolution resolution = Resolution.Daily)</code> in the <code>Initialize</code> method of your algorithm. This method gives LEAN the T-type factory to create the objects, the name of the data, and the resolution at which to poll the data source for updates.</span>

<span class="python"><code>self.AddData(Type class, string ticker, Resolution resolution = Resolution.Daily)</code>. in the <code>Initialize</code> method of your algorithm. This method gives LEAN the type factory to create the data objects, the name of the data, and the resolution to poll the data source for updates. </span>
</p>

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
        self.symbol = self.AddData(MyCustomDataType, "&lt;name&gt;", Resolution.Daily).Symbol
</pre>
</div>

<p>The <code>resolution</code> argument should match the resolution of your custom dataset. The lowest reasonable resolution is every minute. Anything more frequent than every minute is very slow to execute. The frequency that LEAN checks the data source depends on the <code>resolution</code> argument. The following table shows the polling frequency of each resolution:</p> 

<?php include(DOCS_RESOURCES."/datasets/live-dataset-polling-frequency-table.html"); ?>
