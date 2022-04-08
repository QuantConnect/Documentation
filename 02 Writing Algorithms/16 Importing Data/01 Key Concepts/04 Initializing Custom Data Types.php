<p>
To receive your custom data in the OnData method, after you define your custom data type, call 
	<span class="csharp"><code>AddData&lt;T&gt;(string ticker, Resolution resolution = Resolution.Daily)</code> in the <code>Initialize</code> method of your algorithm. 
		This gives LEAN the T-type factory to create the objects, the name of the data, and the resolution at which to poll 
		the data to check for updates.
	</span><span class="python"><code>self.AddData(Type class, string ticker, Resolution resolution = Resolution.Daily)</code>. in the <code>Initialize</code> method of your algorithm. This gives LEAN the 
		type factory to create the data objects and the resolution to poll the remote data source for updates. 
	</span>
</p>

<div class="section-example-container">
	<pre class="csharp">// In Initialize method:
AddData&lt;MyCustomDataType&gt;("&lt;name&gt;", Resolution.Daily);
</pre>
	<pre class="python"># In Initialize method:
self.AddData(MyCustomDataType, "&lt;name&gt;", Resolution.Daily)
</pre>
</div>

## TODO: Update to save symbol so that we can access the custom data in OnData, also add example of accessing in OnData

<p>
	The framework checks for new data as instructed by the <code>Resolution</code> period.The following table shows the polling frequency of each resolution:</p> 

<?php include(DOCS_RESOURCES."/datasets/live-dataset-polling-frequency-table.html"); ?>
