// Request history for specific symbol, default to AddSecurity resolution.
IEnumerable<TradeBar> bars = History("SPY", TimeSpan.FromDays(7));
// Request history for specific symbol, at specific resolution.
IEnumerable<TradeBar> bars = History("SPY", TimeSpan.FromDays(7), Resolution.Minute);

//Get last 14 bars of SPY, default to AddSecurity resolution.
//Note you can't get "14 ticks" -- getting a specific number of bars only applies to TradeBar data.
IEnumerable<TradeBar> bars = History("SPY", 14);
//Get last 14 bars of SPY, at specific resolution.
IEnumerable<TradeBar> bars = History("SPY", 14, Resolution.Minute);

// Request history for specific symbol, default to AddSecurity resolution.
IEnumerable<QuoteBar> bars = History<QuoteBar>("EURUSD", TimeSpan.FromDays(7));
// Request history for specific symbol, at specific resolution.
IEnumerable<QuoteBar> bars = History<QuoteBar>("EURUSD", TimeSpan.FromDays(7), Resolution.Minute);

//Get last 14 bars of EURUSD, default to AddSecurity resolution.
//Note you can't get "14 ticks" -- getting a specific number of bars only applies to QuoteBar data.
IEnumerable<QuoteBar> bars = History<QuoteBar>("EURUSD", 14);
//Get last 14 bars of EURUSD, at specific resolution.
IEnumerable<QuoteBar> bars = History<QuoteBar>("EURUSD", 14, Resolution.Minute);

// Get history for all tickers we're subscribed to, at their native resolution
IEnumerable<Slice> slices = History(TimeSpan.FromDays(7));
// Get history for all tickers we're subscribed to, at a specific resolution
IEnumerable<Slice> slices = History(TimeSpan.FromDays(7), Resolution.Minute);

// For custom data, we need to define the type. E.g.: Quandl
AddData<Quandl>("SYMBOL")
IEnumerable<Quandl> bars = History<Quandl>("SYMBOL", TimeSpan.FromDays(7));
IEnumerable<Quandl> bars = History<Quandl>("SYMBOL", 14);
