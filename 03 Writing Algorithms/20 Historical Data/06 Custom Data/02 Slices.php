<!-- This php file is the same as the alternative data one, except just changed the custom word for alternative word. -->

<p class='csharp'>
  To request <code>Slice</code> objects of historical data, call the <code>History</code> method.
  If you pass a list of <code>Symbol</code> objects, it returns data for all the custom datasets that the <code>Symbol</code> objects reference.
</p>

<div class="csharp section-example-container">
      <pre class="csharp">// Get the latest 3 data points of some custom datasets, packaged into Slice objects.
var history = History(datasetSymbols, 3);</pre>
</div>

<p class='csharp'>If you don't pass any <code>Symbol</code> objects, it returns data for all the data subscriptions in your <?=$writingEnvironment ? "algorithm" : "notebook" ?>, so the result may include more than just custom data.</p>

<p class='python'>
  To request <code>Slice</code> objects of historical data, call the <code>history</code> method without providing any <code>Symbol</code> objects.
  It returns data for all the data subscriptions in your <?=$writingEnvironment ? "algorithm" : "notebook" ?>, so the result may include more than just custom data.
</p>

<div class="section-example-container">
      <pre class="csharp">// Get the latest 3 data points of all the securities/datasets in the <?=$writingEnvironment ? "algorithm" : "notebook" ?>, packaged into Slice objects.
var history = History(3);</pre>
      <pre class="python"># Get the latest 3 data points of all the securities/datasets in the <?=$writingEnvironment ? "algorithm" : "notebook" ?>, packaged into Slice objects.
history = self.history(3)</pre>
</div>

<p>
  When your history request returns <code>Slice</code> objects, the <code class="csharp">Time</code><code class="python">time</code> properties of these objects are based on the <?=$writingAlgorithms ? "algorithm" : "notebook" ?> time zone, but the <code class="csharp">EndTime</code><code class="python">end_time</code> properties of the individual data objects are based on the <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/time-zones#05-Data-Time-Zone'>data time zone</a>. 
  The <code class="csharp">EndTime</code><code class="python">end_time</code> is the end of the sampling period and when the data is actually available. 
</p>
