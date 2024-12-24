<?
$imgLink = "https://cdn.quantconnect.com/i/tu/custom-dataset-dataframe-history.png";
?>

<p class='csharp'>
  To get a list of historical data points for a custom dataset, call the <code>History&lt;<span class='placeholder-text'>customDatasetClass</span>&gt;</code> method with the dataset <code>Symbol</code>.
</p>

<p class='python'>
  To get historical data points for a custom dataset, call the <code>history</code> method with the dataset <code>Symbol</code>.
  This method returns a DataFrame that contains the data point attributes of the dataset class.
</p>

<div class="section-example-container">
    <pre class="csharp">// Define the custom data class outside of the algorithm class.
public class MyCustomDataType : BaseData
{
    public decimal Open;
    public decimal High;
    public decimal Low;
    public decimal Close;

    public override SubscriptionDataSource GetSource(SubscriptionDataConfig config, DateTime date, bool isLiveMode)
    {
        if (!isLiveMode)
        {
            // In a backtest, load the file from the ObjectStore to increase the speed of the algorithm.
            return new SubscriptionDataSource(
                "&lt;CustomDataKey&gt;", SubscriptionTransportMedium.ObjectStore, FileFormat.Csv
            );
        }
        return new SubscriptionDataSource(
            "https://raw.githubusercontent.com/QuantConnect/Documentation/master/Resources/datasets/custom-data/csv-data-example.csv", 
            SubscriptionTransportMedium.RemoteFile
        );
    }

    public override BaseData Reader(SubscriptionDataConfig config, string line, DateTime date, bool isLiveMode)
    {
        if (string.IsNullOrWhiteSpace(line.Trim()))
        {
            return null;
        }

        var index = new MyCustomDataType();

        try
        {
            var data = line.Split(',');
            index.Symbol = config.Symbol;
            index.Time = DateTime.Parse(data[0], CultureInfo.InvariantCulture);
            index.EndTime = index.Time.AddDays(1);
            index.Open = Convert.ToDecimal(data[1], CultureInfo.InvariantCulture);
            index.High = Convert.ToDecimal(data[2], CultureInfo.InvariantCulture);
            index.Low = Convert.ToDecimal(data[3], CultureInfo.InvariantCulture);
            index.Close = Convert.ToDecimal(data[4], CultureInfo.InvariantCulture);
            index.Value = index.Close;
        }
        catch
        {
        }
        return index;
    }
}
      
// In the Initialize method, add a custom dataset and save a reference to it's Symbol.
var datasetSymbol = AddData&lt;MyCustomDataType&gt;("MyCustomDataType", Resolution.Daily).Symbol;
// Get the trailing 5 days of MyCustomDataType data.
var history = History&lt;MyCustomDataType&gt;(datasetSymbol, 5, Resolution.Daily);</pre>
    <pre class="python"># Define the custom data class outside of the algorithm class.
class MyCustomDataType(PythonData):

    def get_source(self, config: SubscriptionDataConfig, date: datetime, is_live: bool) -&gt; SubscriptionDataSource:
        if not is_live:
            # In a backtest, load the file from the ObjectStore to increase the speed of the algorithm.
            return SubscriptionDataSource(
                "&lt;custom_data_key&gt;", SubscriptionTransportMedium.OBJECT_STORE, FileFormat.CSV
            )
        return SubscriptionDataSource(
            "https://raw.githubusercontent.com/QuantConnect/Documentation/master/Resources/datasets/custom-data/csv-data-example.csv", 
            SubscriptionTransportMedium.REMOTE_FILE
        )

    def reader(self, config: SubscriptionDataConfig, line: str, date: datetime, is_live: bool) -&gt; BaseData:
        if not (line.strip()):
            return None
        index = MyCustomDataType()
        index.symbol = config.symbol
        try:
            data = line.split(',')
            index.time = datetime.strptime(data[0], "%Y-%m-%d")
            index.end_time = index.time + timedelta(days=1)
            index.value = data[4]
            index["open"] = float(data[1])
            index["high"] = float(data[2])
            index["low"] = float(data[3])
            index["close"] = float(data[4])
        except ValueError:
            # Do nothing
            return None
        return index


# In the initialize method, add a custom dataset and save a reference to it's Symbol.
dataset_symbol = self.add_data(MyCustomDataType, "MyCustomDataType", Resolution.DAILY).symbol
# Get the trailing 5 days of MyCustomDataType data in DataFrame format.
history = self.history(dataset_symbol, 5, Resolution.DAILY)</pre>
</div>

<img class='python docs-image' src='<?=$imgLink?>' alt='DataFrame of MyCustomDataType data.'>

<p class='python'>To get a list of dataset objects instead of a DataFrame, call the <code>history[<span class='placeholder-text'>customDatasetClass</span>]</code> method.</p>

<div class="python section-example-container">
    <pre class="python"># Get the trailing 5 days of MyCustomDataType data for an asset in MyCustomDataType format. 
history = self.history[MyCustomDataType](dataset_symbol, 5, Resolution.DAILY)</pre>
</div>


<div class='python'>
  <p>
    If the <code class='python'>get_source</code><code class='csharp'>GetSource</code> method of your custom data class returns a <code>SubscriptionDataSource</code> that uses <code class='python'>FileFormat.UNFOLDING_COLLECTION</code><code class='csharp'>FileFormat.UnfoldingCollection</code>, the dataset provide multiple entries per <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>time step</a>. 
    In this case, to organize the data into a DataFrame, set the <code>flatten</code> argument to <code>True</code>.
  </p>

  <div class="section-example-container">
    <pre class="python">history = self.history(dataset_symbol, 1, Resolution.DAILY, flatten=True)</pre>
  </div>
</div>
