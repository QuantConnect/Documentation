# Example custom universe data; it is virtually identical to other custom data types.
class NyseTopGainers(PythonData):

    def GetSource(self, config, date, isLiveMode):
        return SubscriptionDataSource(@"your-remote-universe-data", SubscriptionTransportMedium.RemoteFile)
    
    def Reader(self, config, line, date, isLiveMode):
        # Generate required data, then return an instance of your class.
        nyse = NyseTopGainers()
        nyse.Time = date
        # define end time as exactly 1 day after Time
        nyse.EndTime = nyse.Time + timedelta(1)
        nyse.Symbol = Symbol.Create(symbolString, SecurityType.Equity, Market.USA)
        nyse["Rank"] = rank
        return nyse