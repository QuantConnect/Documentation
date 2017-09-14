# Create, initialize a consolidator and add an indicator
def Initialize(self):
    # ...other initialization...
    self.AddEquity("SPY", Resolution.Minute)
    consolidator = TradeBarConsolidator(30)
    self._sma = SimpleMovingAverage(10)
    self.RegisterIndicator("SPY", self._sma, consolidator)
    self.SubscriptionManager.AddConsolidator("SPY", consolidator)