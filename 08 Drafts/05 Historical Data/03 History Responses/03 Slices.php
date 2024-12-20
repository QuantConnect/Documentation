<p class='csharp'>
  To request <code>Slice</code> objects of historical data, call the <code>History</code> method.
  If you pass a list of <code>Symbol</code> objects, it returns data for all the securities and datasets that the <code>Symbol</code> objects reference.
</p>

<div class="csharp section-example-container">
      <pre class="csharp">// Get the latest 3 data points of some securities/datasets, packaged into Slice objects.
var history = History(symbols, 3);</pre>
</div>

<p class='csharp'>If you don't pass any <code>Symbol</code> objects, it returns data for all the data subscriptions in your <?=$writingEnvironment ? "algorithm" : "notebook" ?>.</p>

<p class='python'>
  To request <code>Slice</code> objects of historical data, call the <code>history</code> method without providing any <code>Symbol</code> objects.
  It returns data for all the data subscriptions in your <?=$writingEnvironment ? "algorithm" : "notebook" ?>.
</p>

<div class="section-example-container">
      <pre class="csharp">// Get the latest 3 data points of all the securities/datasets in the <?=$writingEnvironment ? "algorithm" : "notebook" ?>, packaged into Slice objects.
var history = History(3);</pre>
      <pre class="python"># Get the latest 3 data points of all the securities/datasets in the <?=$writingEnvironment ? "algorithm" : "notebook" ?>, packaged into Slice objects.
history = self.history(3)</pre>
</div>

<p>
  When your history request returns <code>Slice</code> objects, the <code class="csharp">Time</code><code class="python">time</code> properties of these objects are based on the <?=$writingAlgorithms ? "algorithm" : "notebook" ?> time zone, but the <code class="csharp">EndTime</code><code class="python">end_time</code> properties of the individual data objects (for example, <code>TradeBar</code>, <code>QuoteBar</code>, and <code>Tick</code> objects) are based on the <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/time-zones#05-Data-Time-Zone'>data time zone</a>. 
  The <code class="csharp">EndTime</code><code class="python">end_time</code> is the end of the sampling period and when the data is actually available. 
  For daily US Equity data, this results in data points appearing on Saturday and skipping Monday.
</p>
