// Consolidating minute SPY into Daily SMA/EMA.
var ema = EMA("SPY", 14, Resolution.Daily);
var sma = SMA("SPY", 14, Resolution.Daily);