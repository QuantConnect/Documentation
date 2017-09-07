// In Initialize
AddForex("EURUSD", Resolution.Minute);   // or
AddForex("EURUSD", Resolution.Minute, Market.FXCM);

// For OANDA, we need to explictly define the market
AddForex("EURUSD", Resolution.Minute, Market.Oanda);