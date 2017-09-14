// Register for daily bars from minutely data.
AddEquity("SPY", Resolution.Minute);
var myTradeBarIndicator = new MyTradeBarIndicator(120);
RegisterIndicator("SPY", myTradeBarIndicator, Resolution.Daily);