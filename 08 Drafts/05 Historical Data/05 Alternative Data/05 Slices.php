<p class='csharp'>
  To request <code>Slice</code> objects of historical data, call the <code>History</code> method.
  If you pass a list of <code>Symbol</code> objects, it returns data for all the alternative datasets that the <code>Symbol</code> objects reference.
</p>

<div class="csharp section-example-container">
      <pre class="csharp">// Add an alternative dataset.
var symbol = AddCrypto("BTCUSD", Resolution.Daily, Market.Bitfinex).Symbol;
var datasetSymbol = AddData&lt;BitcoinMetadata&gt;(symbol).Symbol;
// Get the latest 3 data points of some alternative dataset(s), packaged into Slice objects.
var history = History(new[] { datasetSymbol }, 3);
// Iterate through each Slice and get the alternative data points.
foreach (var slice in history)
{
    Log($"Hash rate at {slice.Time}: {slice[datasetSymbol].HashRate}");
}</pre>
</div>
<div class="csharp section-example-container">
      <pre>Hash rate at 6/23/2023 8:00:00 PM: 325294307.5702
Hash rate at 6/24/2023 8:00:00 PM: 379943751.242
Hash rate at 6/25/2023 8:00:00 PM: 398160232.466</pre>
</div>

<p class='csharp'>If you don't pass any <code>Symbol</code> objects, it returns data for all the data subscriptions in your <?=$writingEnvironment ? "algorithm" : "notebook" ?>, so the result may include more than just alternative data.</p>

<p class='python'>
  To request <code>Slice</code> objects of historical data, call the <code>history</code> method without providing any <code>Symbol</code> objects.
  It returns data for all the data subscriptions in your <?=$writingEnvironment ? "algorithm" : "notebook" ?>, so the result may include more than just alternative data.
</p>

<div class="section-example-container">
      <pre class="csharp">// Get the latest 3 data points of all the securities/datasets in the <?=$writingEnvironment ? "algorithm" : "notebook" ?>, packaged into Slice objects.
var history = History(3);
// Iterate through each Slice and get the synchronized data points at each moment in time.
foreach (var slice in history)
{
    Log($"Time: {slice.Time}");
    if (slice.ContainsKey(symbol))
    {
        Log($"Price: {slice[symbol].Price}");
    }
    if (slice.ContainsKey(datasetSymbol))
    {
        Log($"Hash rate: {((BitcoinMetadata)slice[datasetSymbol]).HashRate}");
    }
}</pre>
      <pre class="python"># Add an asset and an alternative dataset.
symbol = self.add_crypto('BTCUSD', Resolution.DAILY, Market.BITFINEX).symbol
dataset_symbol = self.add_data(BitcoinMetadata, symbol).symbol
# Get the latest 3 data points of all the securities/datasets in the <?=$writingEnvironment ? "algorithm" : "notebook" ?>, packaged into Slice objects.
history = self.history(3)
# Iterate through each Slice and get the synchronized data points at each moment in time.
for slice_ in history:
    self.log(f"Time: {slice_.time}")
    if symbol in slice_:
        self.log(f"Price: {slice_[symbol].price}")
    if dataset_symbol in slice_:
        self.log(f"Hash rate: {slice_[dataset_symbol].hash_rate}")</pre>
</div>

<div class="csharp section-example-container">
      <pre>Time: 6/23/2023 8:00:00 PM
Price: 30712
Hash rate: 325294307.5702
Time: 6/24/2023 8:00:00 PM
Price: 30556
Hash rate: 379943751.242
Time: 6/25/2023 8:00:00 PM
Price: 30492
Hash rate: 398160232.466</pre>
</div>
<div class="python section-example-container">
      <pre>Time: 2023-06-23 20:00:00
Price: 30712.0
Hash rate: 325294307.5702
Time: 2023-06-24 20:00:00
Price: 30556.0
Hash rate: 379943751.242
Time: 2023-06-25 20:00:00
Price: 30492.0
Hash rate: 398160232.466</pre>
</div>

<p>
  When your history request returns <code>Slice</code> objects, the <code class="csharp">Time</code><code class="python">time</code> properties of these objects are based on the <?=$writingAlgorithms ? "algorithm" : "notebook" ?> time zone, but the <code class="csharp">EndTime</code><code class="python">end_time</code> properties of the individual data objects are based on the <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/time-zones#05-Data-Time-Zone'>data time zone</a>. 
  The <code class="csharp">EndTime</code><code class="python">end_time</code> is the end of the <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/periods'>sampling period</a> and when the data is actually available. 
</p>
