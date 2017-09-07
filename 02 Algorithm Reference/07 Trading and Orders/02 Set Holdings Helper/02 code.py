# Set fixed percentages of known tickers:
self.SetHoldings("IBM", 0.25)
self.SetHoldings("GOOG", 0.25)
self.SetHoldings("AAPL", 0.25)
self.SetHoldings("MSFT", 0.25)

# Or set portfolio to equal weighting of all securities:
weighting = 1.0 / self.Securities.Count
for security in self.Securities.Values:
    self.SetHoldings(security.Symbol, weighting)