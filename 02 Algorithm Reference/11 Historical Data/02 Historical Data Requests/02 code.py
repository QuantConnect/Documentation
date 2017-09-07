# Get history for all tickers we're subscribed to, at their native resolution
slices = self.History(timedelta(7))
# Get history for all tickers we're subscribed to, at a specific resolution
slices = self.History(timedelta(7), Resolution.Minute)

# Equity case:
# Request history for specific symbol, default to AddSecurity resolution.
bars = self.History("SPY", TimeSpan.FromDays(7))
# Request history for specific symbol, at specific resolution.
bars = self.History("SPY", TimeSpan.FromDays(7), Resolution.Minute)

# Get last 14 bars of SPY, default to AddSecurity resolution.
# Note you can't get "14 ticks" -- getting a specific number of bars only applies to TradeBar data.
bars = self.History("SPY", 14)
# Get last 14 bars of SPY, at specific resolution.
bars = self.History("SPY", 14, Resolution.Minute)

# Other assets (QuoteBar) case:
# We need to use map method to select only the specific symbol
bars = map(lambda x: x["EURUSD"], self.History(timedelta(7)))
bars = map(lambda x: x["EURUSD"], self.History(timedelta(7), Resolution.Minute))
bars = map(lambda x: x["EURUSD"], self.History(14))
bars = map(lambda x: x["EURUSD"], self.History(14, Resolution.Minute))

# Get history in pandas.DataFrame format, use list of string
df = self.History(["EURUSD"], timedelta(7))
df = self.History(["EURUSD"], timedelta(7), Resolution.Minute)
df = self.History(["EURUSD"], 14)
df = self.History(["EURUSD"], 14, Resolution.Minute)