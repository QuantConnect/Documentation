<p>To receive your custom data in the <code>OnData</code> method, create a custom type and then create a data subscription. The custom data type tells LEAN where to get your data and how to read it.</p>

<p>All custom data types must extend the <code class='csharp'>BaseData</code><code class='python'>PythonData</code> class and override the <code>GetSource</code> and <code>Reader</code> methods</p>

<div class="section-example-container">
    <pre class="csharp">public class MyCustomDataType : BaseData
{
    public override DateTime EndTime { get; set; }
    public decimal Property1 { get; set; } = 0;

    public override SubscriptionDataSource GetSource(
        SubscriptionDataConfig config,
        DateTime date,
        bool isLive)
    {
        return new SubscriptionDataSource("&lt;sourceURL&gt;", SubscriptionTransportMedium.RemoteFile);
    }

    public override BaseData Reader(
        SubscriptionDataConfig config,
        string line,
        DateTime date,
        bool isLive)
    {
        if (string.IsNullOrWhiteSpace(line.Trim()) || char.IsDigit(line[0]))
        {
            return null;
        }

        var data = line.Split(',');
        return new MyCustomDataType()
        {
            Time = DateTime.ParseExact(data[0], "yyyyMMdd", CultureInfo.InvariantCulture),
            EndTime = Time.AddDays(1),
            Symbol = config.Symbol,
            Value = data[1].IfNotNullOrEmpty(
                s => decimal.Parse(s, NumberStyles.Any, CultureInfo.InvariantCulture)),
            Property1 = data[2].IfNotNullOrEmpty(
                s => decimal.Parse(s, NumberStyles.Any, CultureInfo.InvariantCulture))
        };
    }
}</pre>
    <pre class="python">class MyCustomDataType(PythonData):
    def GetSource(self,
         config: SubscriptionDataConfig,
         date: datetime,
         isLive: bool) -&gt; SubscriptionDataSource:
        return SubscriptionDataSource("&lt;sourceURL&gt;", SubscriptionTransportMedium.RemoteFile)

    def Reader(self,
         config: SubscriptionDataConfig,
         line: str,
         date: datetime,
         isLive: bool) -&gt; BaseData:

         if not (line.strip() and line[0].isdigit()):
            return None

         data = line.split(',')

        custom = MyCustomDataType()
        custom.Time = datetime.strptime(data[0], '%Y%m%d')
        custom.EndTime = custom.Time + timedelta(1)
        custom.Value = float(data[1])
        custom["Property1"] = float(data[2])
        return custom</pre>
</div>

<p>For more information about custom data types, see <a href='/docs/v2/writing-algorithms/importing-data/streaming-data/key-concepts'>Streaming Data</a>.</p>