<?
$imgLink = "https://cdn.quantconnect.com/i/tu/custom-dataset-universe-dataframe-history.png";
?>

<p class='csharp'>
  To get historical data for a <a href='/docs/v2/writing-algorithms/importing-data/streaming-data/custom-universes/key-concepts'>custom data universe</a>, call the <code>History</code> method with the <code>Universe</code> object.
  For an example definition of a custom data universe class, see the <a href='/docs/v2/writing-algorithms/importing-data/streaming-data/custom-universes/csv-format-example'>CSV Format Example</a>.
</p>

<p class='python'>
  To get historical data for a <a href='/docs/v2/writing-algorithms/importing-data/streaming-data/custom-universes/key-concepts'>custom data universe</a>, call the <code>history</code> method with the <code>Universe</code> object.
  <!-- Set the <code>flatten</code> argument to <code>True</code> to get a DataFrame that has columns for the data point attributes. -->
  For an example definition of a custom data universe class, see the <a href='/docs/v2/writing-algorithms/importing-data/streaming-data/custom-universes/csv-format-example'>CSV Format Example</a>.
</p>

<div class="section-example-container">
    <pre class="csharp">public class CustomDataUniverseHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2017, 7, 9);
        // Add a universe from a custom data source and save a reference to it.
        var universe = AddUniverse&lt;StockDataSource&gt;(
            "myStockDataSource", Resolution.Daily, data => data.Select(x => x.Symbol)
        );
        // Get the historical universe data over the last 5 days.
        var history = History(universe, TimeSpan.FromDays(5)).Cast&lt;StockDataSource&gt;().ToList();
        // Iterate through each day in the universe history and count the number of constituents.
        foreach (var stockData in history)
        {
            var t = stockData.Time;
            var size = stockData.Symbols.Count;
        }
    }
}</pre>
    <pre class="python">class CustomDataUniverseHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2017, 7, 9)
        # Add a universe from a custom data source and save a reference to it.
        universe = self.add_universe(
            StockDataSource, "my-stock-data-source", Resolution.DAILY, lambda data: [x.symbol for x in data]
        )
        # Get the historical universe data over the last 5 days in DataFrame format.
        history = self.history(universe, timedelta(5))</pre>
</div>

<table border="1" class="dataframe python">
  <thead>
    <tr style="text-align: right;">
      <th></th>
      <th>symbols</th>
    </tr>
    <tr>
      <th>time</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>2017-07-05</th>
      <td>[SPY, QQQ, FB, AAPL, IWM]</td>
    </tr>
    <tr>
      <th>2017-07-06</th>
      <td>[SPY, QQQ, FB, AAPL, IWM]</td>
    </tr>
    <tr>
      <th>2017-07-07</th>
      <td>[QQQ, AAPL, IWM, FB, GOOGL]</td>
    </tr>
    <tr>
      <th>2017-07-08</th>
      <td>[IWM, AAPL, FB, BAC, GOOGL]</td>
    </tr>
    <tr>
      <th>2017-07-09</th>
      <td>[AAPL, FB, GOOGL, GOOG, BAC]</td>
    </tr>
  </tbody>
</table>


<div class="python section-example-container">
    <pre class="python"># Count the number of assets in the universe each day.
universe_size_by_day = history.apply(lambda row: len(row['symbols']), axis=1)</pre>
</div>

<div class="python section-example-container">
    <pre>time
2017-07-05    5
2017-07-06    5
2017-07-07    5
2017-07-08    5
2017-07-09    5
Name: symbols, dtype: int64</pre>
</div>




