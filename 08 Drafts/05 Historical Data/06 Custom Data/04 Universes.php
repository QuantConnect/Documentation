<?
$imgLink = "https://cdn.quantconnect.com/i/tu/custom-dataset-universe-dataframe-history.png";
?>

<p class='csharp'>
  To get historical data for an <a href='/docs/v2/writing-algorithms/importing-data/streaming-data/custom-universes/key-concepts'>custom data universe</a>, call the <code>History</code> method with the <code>Universe</code> object.
</p>

<p class='python'>
  To get historical data for an <a href='/docs/v2/writing-algorithms/importing-data/streaming-data/custom-universes/key-concepts'>custom data universe</a>, call the <code>history</code> method with the <code>Universe</code> object.
  <!-- Set the <code>flatten</code> argument to <code>True</code> to get a DataFrame that has columns for the data point attributes. -->
</p>

<div class="section-example-container">
    <pre class="csharp">// Add a universe from a custom data source and save a reference to it.
AddUniverse&lt;StockDataSource&gt;("myStockDataSource", Resolution.Daily, data => data.Select(x => x.Symbol));
// Get the historical universe data over the last 5 days.
var history = History(universe, TimeSpan.FromDays(5));</pre>
    <pre class="python"># Add a universe from a custom data source and save a reference to it.
universe = self.add_universe(
    StockDataSource, "my-stock-data-source", Resolution.DAILY, lambda data: [x.symbol for x in data]
)
# Get the historical universe data over the last 5 days in DataFrame format.
history = self.history(universe, timedelta(5))</pre>
</div>

<img class='python docs-image' src='<?=$imgLink?>' alt='DataFrame of universe data for a custom dataset.'>


