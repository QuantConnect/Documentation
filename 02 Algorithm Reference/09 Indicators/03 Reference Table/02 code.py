# 1. Using basic indicator at the same resolution as source security:
self.ema = self.EMA("IBM", 14)
self.rsi = self.RSI("IBM", 14)

#2. Using indicator at different (higher) resolution to the source security:
self.emaDaily = self.EMA("IBM", 14, Resolution.Daily)

#3. Indicator of a different property (default is close of bar/data):
self.emaDailyHigh = self.EMA("IBM", 14, Resolution.Daily, Field.High)
