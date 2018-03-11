// 1. Using basic indicator at the same resolution as source security:
// TIP -> You can use string "IBM" or the security.Symbol object
var ema = EMA("IBM", 14);
var rsi = RSI("IBM", 14);

//2. Using indicator at different (higher) resolution to the source security:
var emaDaily = EMA("IBM", 14, Resolution.Daily);

//3. Indicator of a different property (default is close of bar/data):
// TIP -> You can use helper methods Field.Open, Field.High etc on the indicator selector:
var emaDailyHigh = EMA("IBM", 14, Resolution.Daily, point => ((TradeBar) point).High);

//NOTE. Some indicators require tradebars (ATR, AROON) so your selector must return a TradeBar object for those indicators.
