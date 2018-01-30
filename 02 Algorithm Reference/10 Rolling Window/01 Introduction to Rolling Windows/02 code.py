# In Initialize, create the rolling windows
def Initialize(self):
    # Create a Rolling Window to keep the 4 decimal
    self.closeWindow = RollingWindow[decimal](4)
    # Create a Rolling Window to keep the 2 TradeBar
    self.tradeBarWindow = RollingWindow[TradeBar](2)
    # Create a Rolling Window to keep the 2 QuoteBar
    self.quoteBarWindow = RollingWindow[QuoteBar](2)

# In OnData, update the rolling windows
 def OnData(self, data):
    # Add SPY bar close in the rolling window
    self.closeWindow.Add(data["SPY"].Close)
    # Add SPY TradeBar in rolling window
    self.tradeBarWindow.Add(data["SPY"])
    # Add EURUSD QuoteBar in rolling window
    self.quoteBarWindow.Add(data["EURUSD"])