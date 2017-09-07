//Example custom universe data; it is virtually identical to other custom data types.
public class NyseTopGainers : BaseData {
    public int TopGainersRank;
    public override DateTime EndTime {
        // define end time as exactly 1 day after Time
	get { return Time + QuantConnect.Time.OneDay; }
	set { Time = value - QuantConnect.Time.OneDay; }
    }

    public override SubscriptionDataSource GetSource(SubscriptionDataConfig config, DateTime date, bool isLiveMode) {
        return new SubscriptionDataSource(@"your-remote-universe-data", SubscriptionTransportMedium.RemoteFile);
     }

     public override BaseData Reader(SubscriptionDataConfig config, string line, DateTime date, bool isLiveMode) {
         // Generate required data, then return an instance of your class.
        return new NyseTopGainers {
            Symbol = Symbol.Create(symbolString, SecurityType.Equity, Market.USA),
            Time = date,
            TopGainersRank = rank
        };
    }
}