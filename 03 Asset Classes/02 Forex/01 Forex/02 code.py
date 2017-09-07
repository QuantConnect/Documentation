# In Initialize
self.AddForex("EURUSD", Resolution.Minute)   # or
self.AddForex("EURUSD", Resolution.Minute, Market.FXCM)

# For OANDA, we need to explictly define the market
self.AddForex("EURUSD", Resolution.Minute, Market.Oanda)