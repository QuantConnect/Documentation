public class Weather : BaseData
{
    public decimal MaxC = 0;
    public decimal MinC = 0;
    public string errString = "";

    public override SubscriptionDataSource GetSource(
        SubscriptionDataConfig config, 
        DateTime date, 
        bool isLive)
    {
        var source = string.Format(
                "https://www.wunderground.com/history/airport/{0}/{1}/1/1/CustomHistory.html?dayend=31&monthend=12&yearend={1}&format=1",
                config.Symbol, date.Year);

          return new SubscriptionDataSource(source,
              SubscriptionTransportMedium.RemoteFile);
    }

    public override BaseData Reader(
        SubscriptionDataConfig config,
        string line,
        DateTime date,
        bool isLive)
    {
        if (string.IsNullOrWhiteSpace(line) ||
            char.IsLetter(line[0])) 
            return null;
            
        var data = line.Split(',');

        return new Weather()
        {
            // Make sure we only get this data AFTER trading day - don't want forward bias.
            Time = DateTime.Parse(data[0]).AddHours(20), 
            Symbol = config.Symbol,
            MaxC = Convert.ToDecimal(data[1]),
            Value = Convert.ToDecimal(data[2]),
            MinC = Convert.ToDecimal(data[3]),
        };
    }
}