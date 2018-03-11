# 1. Using basic indicator at the same resolution as source security:
self.ema = self.EMA("IBM", 14)
self.rsi = self.RSI("IBM", 14)

#2. Using indicator at different (higher) resolution to the source security:
self.emaDaily = self.EMA("IBM", 14, Resolution.Daily)

#3. Indicator of a different property (default is close of bar/data):
self.emaDailyHigh = self.EMA("IBM", 14, Resolution.Daily, Field.High)


#4. Using the indicators:
#4.1  Setup in initialize: make sure you've asked for the data for the asset.
self.AddEquity("IBM")
self.emaFast = self.EMA("IBM", 14);
self.emaSlow = self.EMA("IBM", 28);

#4.2 Consume the indicators in OnData.
if self.emaSlow.IsReady and self.emaFast.IsReady:
    if self.emaFast.Current.Value > self.emaSlow.Current.Value:
        self.Debug("Long")
    elif self.emaFast.Current.Value < self.emaSlow.Current.Value:
        self.Debug("Short")
