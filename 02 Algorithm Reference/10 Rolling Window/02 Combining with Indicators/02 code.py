# In Initialize, create the rolling windows
def Initialize(self):
    # Creates an indicator and adds to a rolling window when it is updated
    self.SMA("SPY", 5).Updated += self.SmaUpdated
    self.smaWin = RollingWindow[IndicatorDataPoint](5)

# Adds updated values to rolling window
def SmaUpdated(self, sender, updated):
    self.smaWin.Add(updated)