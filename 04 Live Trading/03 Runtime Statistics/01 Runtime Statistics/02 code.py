# User displays the current value of BTC
btc = self.Securities["BTC"].Symbol
self.SetRuntimeStatistic("BTC", data[btc].Close)