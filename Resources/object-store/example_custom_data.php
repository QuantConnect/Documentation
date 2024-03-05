<p>Follow these steps to use the Object Store as a data source for <a href='/docs/v2/writing-algorithms/importing-data/streaming-data/key-concepts'>Custom Data</a>:</p>

<ol>
    <li>Create a custom data class that defines a storage key and implement the <code>GetSource</code> method.</li>
    <div class='section-example-container'>
    <pre class='csharp'>public class Bitstamp : TradeBar
{
    public static string KEY = "bitstampusd.csv";
            
    public override SubscriptionDataSource GetSource(SubscriptionDataConfig config, DateTime date, bool isLiveMode)
    {
        return new SubscriptionDataSource(KEY, SubscriptionTransportMedium.ObjectStore);
    }
}    </pre>
    <pre class='python'>class Bitstamp(PythonData):
    KEY = 'bitstampusd.csv'
    def GetSource(self, config, date, isLiveMode):
        return SubscriptionDataSource(Bitstamp.KEY, SubscriptionTransportMedium.ObjectStore)</pre>
    </div>

    <li>Create an algorithm that download data from an external source, and save it to the Object Store.</li>
    <div class='section-example-container'>
    <pre class='csharp'>public class ObjectStoreCustomDataAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        if (!ObjectStore.ContainsKey(Bitstamp.KEY))
        {
            var url = "https://raw.githubusercontent.com/QuantConnect/Documentation/master/Resources/datasets/custom-data/bitstampusd.csv";
            var content = Download(url);
            ObjectStore.Save(Bitstamp.KEY, content);
        }   
    }
}</pre>
    <pre class='python'>class ObjectStoreCustomDataAlgorithm(QCAlgorithm):
    def Initialize(self):
        if not self.ObjectStore.ContainsKey(Bitstamp.KEY):
            url = "https://raw.githubusercontent.com/QuantConnect/Documentation/master/Resources/datasets/custom-data/bitstampusd.csv"
            content = self.Download(url)
            self.ObjectStore.Save(Bitstamp.KEY, content)</pre>
    </div>

    <li>Call the <code>AddData</code> method to create a subscription for the custom type.</li>
    <div class='section-example-container'>
    <pre class='csharp'>public class ObjectStoreCustomDataAlgorithm : QCAlgorithm
{
    private Symbol _customDataSymbol;
    public override void Initialize()
    {
        _customDataSymbol = AddData&lt;Bitstamp&gt;("BTC").Symbol;  
    }
}</pre>
    <pre class='python'>class ObjectStoreCustomDataAlgorithm(QCAlgorithm):
    def Initialize(self):
        self.custom_data_symbol = self.AddData(Bitstamp, "BTC").Symbol</pre>
    </div>

    <li>Implement the <code>Reader</code> method for the custom data class.</li>
    <div class='section-example-container'>
    <pre class='csharp'>public class Bitstamp : TradeBar
{
    public override BaseData Reader(SubscriptionDataConfig config, string line, DateTime date, bool isLiveMode)
    {
        //Example Line Format:
        //Date      Open   High    Low     Close   Volume (BTC)    Volume (Currency)   Weighted Price
        //2011-09-13 5.8    6.0     5.65    5.97    58.37138238,    346.0973893944      5.929230648356
        if (!char.IsDigit(line.Trim()[0]))
        {
            return null;
        }

        var coin = new Bitstamp() {Symbol = config.Symbol};

        var data = line.Split(',');
        coin.Value = data[4].IfNotNullOrEmpty(s => decimal.Parse(s, NumberStyles.Any, CultureInfo.InvariantCulture));
        if (coin.Value == 0)
        {
            return null;
        }

        coin.Time = DateTime.Parse(data[0], CultureInfo.InvariantCulture);
        coin.EndTime = coin.Time.AddDays(1);
        coin.Open = data[1].IfNotNullOrEmpty(s => decimal.Parse(s, NumberStyles.Any, CultureInfo.InvariantCulture));
        coin.High = data[2].IfNotNullOrEmpty(s => decimal.Parse(s, NumberStyles.Any, CultureInfo.InvariantCulture));
        coin.Low = data[3].IfNotNullOrEmpty(s => decimal.Parse(s, NumberStyles.Any, CultureInfo.InvariantCulture));
        coin.VolumeBTC = data[5].IfNotNullOrEmpty(s => decimal.Parse(s, NumberStyles.Any, CultureInfo.InvariantCulture));
        coin.Volume = data[6].IfNotNullOrEmpty(s => decimal.Parse(s, NumberStyles.Any, CultureInfo.InvariantCulture));
        coin.WeightedPrice = data[7].IfNotNullOrEmpty(s => decimal.Parse(s, NumberStyles.Any, CultureInfo.InvariantCulture));
        coin.Close = coin.Value;
        return coin;
    }
}</pre>
    <pre class='python'>class Bitstamp(PythonData):
    def Reader(self, config, line, date, isLiveMode):
        # Example Line Format:
        # Date      Open   High    Low     Close   Volume (BTC)    Volume (Currency)   Weighted Price
        # 2011-09-13 5.8    6.0     5.65    5.97    58.37138238,    346.0973893944      5.929230648356
        if not line[0].isdigit():
            return None

        coin = Bitstamp()
        coin.Symbol = config.Symbol
        data = line.split(',')

        # If value is zero, return None
        coin.Value = float(data[4])
        if coin.Value == 0:
            return None

        coin.Time = datetime.strptime(data[0], "%Y-%m-%d")
        coin.EndTime = coin.Time + timedelta(1)
        coin["Open"] = float(data[1])
        coin["High"] = float(data[2])
        coin["Low"] = float(data[3])
        coin["Close"] = coin.Value
        coin["VolumeBTC"] = float(data[5])
        coin["VolumeUSD"] = float(data[6])
        coin["WeightedPrice"] = float(data[7])
        return coin</pre>
    </div>  

    <li>Make a historical data request, log and plot the data from the <code>Slice</code> object to confirm your algorithm is receiving the custom data.</li>
    <div class='section-example-container'>
    <pre class='csharp'>public class ObjectStoreCustomDataAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        var history = History(_customDataSymbol, 200, Resolution.Daily);
        Debug($"We got {history.Count()} items from historical data request of {_customDataSymbol}.");
    }
    public void OnData(Bitstamp data)
    {
        Log($"{data.EndTime}: Close: {data.Close}");
        Plot(_customDataSymbol, "Price", data.Close);
    }
}</pre>
    <pre class='python'>class ObjectStoreCustomDataAlgorithm(QCAlgorithm):
    def Initialize(self):
        history = self.History(Bitstamp, self.custom_data_symbol, 200, Resolution.Daily)
        self.Debug(f"We got {len(history)} items from historical data request of {self.custom_data_symbol}.")
    def OnData(self, slice):
        data = slice.Get(Bitstamp).get( self.custom_data_symbol)
        if not data:
            return

        self.Log(f'{data.EndTime}: Close: {data.Close}')
        self.Plot(self.custom_data_symbol, 'Price', data.Close)</pre>
    </div>
</ol>

<p>The following algorithm provides a full example of a custom data algorithm sourcing from the Object Store:</p>

<div class="qc-embed-frame" style="display: inline-block; position: relative; width: 100%; min-height: 100px; min-width: 300px;">
    <div class="qc-embed-dummy" style="padding-top: 56.25%;"></div>
    <div class="qc-embed-element" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0;">
    <iframe class="csharp qc-embed-backtest" height="100%" width="100%" style="border: 1px solid #ccc; padding: 0; margin: 0;" src="https://www.quantconnect.com/terminal/processCache?request=embedded_backtest_21138929547fc7837b307929ecf28154.html"></iframe>
    <iframe class="python qc-embed-backtest" height="100%" width="100%" style="border: 1px solid #ccc; padding: 0; margin: 0;" src="https://www.quantconnect.com/terminal/processCache?request=embedded_backtest_523043f753e330592e75bba3020aa9d1.html"></iframe>
    </div>
</div>