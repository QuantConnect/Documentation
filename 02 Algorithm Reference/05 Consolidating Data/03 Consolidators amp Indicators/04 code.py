# Register for daily bars from minutely data.
self.AddEquity("SPY", Resolution.Minute)
myTradeBarIndicator = MyTradeBarIndicator(120)
self.RegisterIndicator("SPY", myTradeBarIndicator, Resolution.Daily)