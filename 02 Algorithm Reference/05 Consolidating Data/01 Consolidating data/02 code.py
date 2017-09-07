# Create and initialize a consolidator
def Initialize(self):
    # ...other initialization...
    self.AddEquity("SPY", Resolution.Minute)
    consolidator = TradeBarConsolidator(15)
    consolidator.DataConsolidated += self.OnDataConsolidated
    self.SubscriptionManager.AddConsolidator("SPY", consolidator)

def OnDataConsolidated(self, sender, bar):
    self.Debug(str(self.Time) + " > New Bar!")