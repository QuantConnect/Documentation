<?
$imgLink = "https://cdn.quantconnect.com/i/tu/custom-dataset-universe-dataframe-history.png";
?>

<p class='csharp'>
  To get historical data for an <a href='/docs/v2/writing-algorithms/importing-data/streaming-data/custom-universes/key-concepts'>custom data universe</a>, call the <code>History</code> method with the <code>Universe</code> object.
  For an example definition of a custom data universe class, see the <a href='/docs/v2/writing-algorithms/importing-data/streaming-data/custom-universes/csv-format-example'>CSV Format Example</a>.
</p>

<p class='python'>
  To get historical data for an <a href='/docs/v2/writing-algorithms/importing-data/streaming-data/custom-universes/key-concepts'>custom data universe</a>, call the <code>history</code> method with the <code>Universe</code> object.
  <!-- Set the <code>flatten</code> argument to <code>True</code> to get a DataFrame that has columns for the data point attributes. -->
  For an example definition of a custom data universe class, see the <a href='/docs/v2/writing-algorithms/importing-data/streaming-data/custom-universes/csv-format-example'>CSV Format Example</a>.
</p>

<div class="section-example-container">
    <pre class="csharp">// Define the custom data class outside of the algorithm class.
public class StockDataSource : BaseData
{
    public List&lt;string&gt; Symbols { get; set; } = new();
    public override DateTime EndTime => Time.AddDays(1);

    public override SubscriptionDataSource GetSource(SubscriptionDataConfig config, DateTime date, bool isLiveMode)
    {
        return new SubscriptionDataSource(
            "https://raw.githubusercontent.com/QuantConnect/Documentation/master/Resources/datasets/custom-data/csv-universe-example.csv", 
            SubscriptionTransportMedium.RemoteFile, 
            FileFormat.Csv
        );
    }

    public override BaseData Reader(SubscriptionDataConfig config, string line, DateTime date, bool isLiveMode)
    {
        var stocks = new StockDataSource();
        try
        {
            var csv = line.Split(',');
            stocks.Time = DateTime.ParseExact(csv[0], "yyyyMMdd", null);
            stocks.Symbols.AddRange(csv.Skip(1));
        }
        catch { return null; }
        return stocks;
    }
}


// In the Initialize method, add a universe from a custom data source and save a reference to it.
var universe = AddUniverse&lt;StockDataSource&gt;("myStockDataSource", Resolution.Daily, data => data.Select(x => x.Symbol));
// Get the historical universe data over the last 5 days.
var history = History(universe, TimeSpan.FromDays(5));</pre>
    <pre class="python"># Define the custom data class outside of the algorithm class.    
class StockDataSource(PythonData):

    def get_source(self, config: SubscriptionDataConfig, date: datetime, is_live: bool) -> SubscriptionDataSource:
        return SubscriptionDataSource(
            "https://raw.githubusercontent.com/QuantConnect/Documentation/master/Resources/datasets/custom-data/csv-universe-example.csv", 
            SubscriptionTransportMedium.REMOTE_FILE, 
            FileFormat.CSV
        )

    def reader(self,config: SubscriptionDataConfig, line: str, date: datetime, is_live: bool) -> BaseData:
        if not (line.strip() and line[0].isdigit()): 
            return None
        stocks = StockDataSource()
        stocks.symbol = config.symbol
        try:
            csv = line.split(',')
            stocks.time = datetime.strptime(csv[0], "%Y%m%d")
            stocks.end_time = stocks.time + timedelta(days=1)
            stocks["Symbols"] = csv[1:]
        except ValueError:
            return None
        return stocks

      
# In the initialize method, add a universe from a custom data source and save a reference to it.
universe = self.add_universe(
    StockDataSource, "my-stock-data-source", Resolution.DAILY, lambda data: [x.symbol for x in data]
)
# Get the historical universe data over the last 5 days in DataFrame format.
history = self.history(universe, timedelta(5))</pre>
</div>

<img class='python docs-image' src='<?=$imgLink?>' alt='DataFrame of universe data for a custom dataset.'>


