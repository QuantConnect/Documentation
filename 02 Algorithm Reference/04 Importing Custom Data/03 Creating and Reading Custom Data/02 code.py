class Weather(PythonData):
    ''' Weather based rebalancing'''
    
    def GetSource(self, config, date, isLive):
        source = "https://www.wunderground.com/history/airport/{0}/{1}/1/1/CustomHistory.html?dayend=31&monthend=12&yearend={1}&format=1".format(config.Symbol, date.year);
        return SubscriptionDataSource(source, SubscriptionTransportMedium.RemoteFile);       


    def Reader(self, config, line, date, isLive):
        # If first character is not digit, pass
        if not (line.strip() and line[0].isdigit()): return None
        
        data = line.split(',')
        weather = Weather()
        weather.Symbol = config.Symbol
        weather.Time = datetime.strptime(data[0], '%Y-%m-%d') + timedelta(hours=20) # Make sure we only get this data AFTER trading day - don't want forward bias.
        weather.Value = decimal.Decimal(data[2])
        weather["MaxC"] = float(data[1])
        weather["MinC"] = float(data[3])

        return weather