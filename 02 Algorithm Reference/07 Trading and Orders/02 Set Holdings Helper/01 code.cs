// Set fixed percentages of known tickers:
SetHoldings("IBM", 0.25);
SetHoldings("GOOG", 0.25);
SetHoldings("AAPL", 0.25);
SetHoldings("MSFT", 0.25);

// Or set portfolio to equal weighting of all securities:
var weighting = 1m / Securities.Count;
foreach(var security in Securities.Values) {
    SetHoldings(security.Symbol, weighting);
}