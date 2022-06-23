<p>Custom universes should extend the <code class="csharp">BaseData</code><code class="python">PythonData</code> class. Extensions of the <code class="csharp">BaseData</code><code class="python">PythonData</code> class must implement a <code>GetSource</code> and <code>Reader</code> method. <br></p>

<p>The <code>GetSource</code> method in your custom data class instructs LEAN where to find the data. This method must return a <code>SubscriptionDataSource</code> object, which contains the data location and format (<code>SubscriptionTransportMedium</code>). You can even change source locations for backtesting and live modes. We support many different data sources.</p>

<p>The <code>Reader</code> method of your custom data class takes one line of data from the source location and parses it into one of your custom objects. You can add as many properties to your custom data objects as you need, but must set <code>Symbol</code> and <code>EndTime</code> properties. When there is no useable data in a line, the method should return <code class="csharp">null</code><code class="python">None</code>. LEAN repeatedly calls the <code>Reader</code> method until the date/time advances or it reaches the end of the file. <br></p>
<div class="section-example-container">
<pre class="csharp">//Example custom universe data; it is virtually identical to other custom data types.
public class MyCustomUniverseDataClass : BaseData 
{
    public int CustomAttribute1;
    public decimal CustomAttribute2;
    public override DateTime EndTime 
    {
        // define end time as exactly 1 day after Time
        get { return Time + QuantConnect.Time.OneDay; }
        set { Time = value - QuantConnect.Time.OneDay; }
    }

    public override SubscriptionDataSource GetSource(SubscriptionDataConfig config, DateTime date, bool isLiveMode)
    {
        return new SubscriptionDataSource(@"your-remote-universe-data", SubscriptionTransportMedium.RemoteFile);
    }

    public override BaseData Reader(SubscriptionDataConfig config, string line, DateTime date, bool isLiveMode) 
    {
        var items = line.Split(",");

        // Generate required data, then return an instance of your class.
        return new MyCustomUniverseDataClass 
        {
            EndTime = Parse.DateTimeExact(items[0], "yyyy-MM-dd"),
            Symbol = Symbol.Create(items[1], SecurityType.Equity, Market.USA),
            CustomAttribute1 = int.Parse(items[2]),
            CustomAttribute2 = decimal.Parse(items[3], NumberStyles.Any, CultureInfo.Invariant)
        };
    }
}
</pre>
<pre class="python"># Example custom universe data; it is virtually identical to other custom data types.
class MyCustomUniverseDataClass(PythonData):

    def GetSource(self, config: SubscriptionDataConfig, date: datetime, isLiveMode: bool) -&gt; SubscriptionDataSource:
        return SubscriptionDataSource(@"your-remote-universe-data", SubscriptionTransportMedium.RemoteFile)

    def Reader(self, config: SubscriptionDataConfig, line: str, date: datetime, isLiveMode: bool) -&gt; BaseData:
        items = line.split(",")
    
        # Generate required data, then return an instance of your class.
        data = MyCustomUniverseDataClass()
        data.EndTime = datetime.strptime(items[0], "%Y-%m-%d")
        # define Time as exactly 1 day earlier Time
        data.Time = data.EndTime - timedelta(1)
        data.Symbol = Symbol.Create(items[1], SecurityType.Crypto, Market.Bitfinex)
        data["CustomAttribute1"] = int(items[2])
        data["CustomAttribute2"] = float(items[3])
        return data
</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/datasets/custom-data/reader-method.html"); ?>

<div class="section-example-container">
<pre class="csharp">public class MyCustomUniverseDataClass : BaseData 
{
    [JsonProperty(PropertyName = "Attr1")]
    public int CustomAttribute1 { get; set; }

    [JsonProperty(PropertyName = "Ticker")]
    public string Ticker { get; set; }
    
    [JsonProperty(PropertyName = "date")]
    public DateTime Date { get; set; }

    public override DateTime EndTime 
    {
        // define end time as exactly 1 day after Time
        get { return Time + QuantConnect.Time.OneDay; }
        set { Time = value - QuantConnect.Time.OneDay; }
    }

    public MyCustomUniverseDataClass()
    {
        Symbol = Symbol.Empty;
        DataType = MarketDataType.Base;
    }
    
    public override SubscriptionDataSource GetSource(SubscriptionDataConfig config, DateTime date, bool isLiveMode)
    {
        return new SubscriptionDataSource(@"your-data-source-url", 
            SubscriptionTransportMedium.RemoteFile,
            FileFormat.UnfoldingCollection);
    }

    public override BaseData Reader(SubscriptionDataConfig config, string line, DateTime date, bool isLiveMode) 
    {
        var items = JsonConvert.DeserializeObject&lt;List&lt;MyCustomUniverseDataClass&gt;&gt;(line);
        var endTime = items.Last().Date;

        foreach (var item in items)
        {
            item.Symbol = Symbol.Create(item.Ticker, SecurityType.Equity, Market.USA);
            item.Time = item.Date;
            item.Value = (decimal) item.CustomAttribute1;
        }

        return new BaseDataCollection(endTime, config.Symbol, items);
    }
}</pre>
<pre class="python">class MyCustomUniverseDataClass(PythonData):
    
    def GetSource(self, config, date, isLive):
        return SubscriptionDataSource("your-data-source-url", SubscriptionTransportMedium.RemoteFile, FileFormat.UnfoldingCollection)

    def Reader(self, config, line, date, isLive):
        json_response = json.loads(line)
        
        endTime = datetime.strptime(json_response[-1]["date"], '%Y-%m-%d') + timedelta(1)

        data = list()

        for json_datum in json_response:
            datum = MyCustomUniverseDataClass()
            datum.Symbol = Symbol.Create(json_datum["Ticker"], SecurityType.Equity, Market.USA)
            datum.Time = datetime.strptime(json_datum["date"], '%Y-%m-%d') 
            datum.EndTime = datum.Time + timedelta(1)
            datum['CustomAttribute1'] = int(json_datum['Attr1'])
            datum.Value = float(json_datum['Attr1'])
            data.append(datum)

        return BaseDataCollection(endTime, config.Symbol, data)</pre>
</div>