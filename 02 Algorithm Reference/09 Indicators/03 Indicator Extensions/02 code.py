class IndicatorTests(QCAlgorithm):
    def Initialize():
       # In addition to other initialize logic:
       self.rsi = self.RSI("SPY", 14)                     # Creating a RSI
       self.rsiSMA = IndicatorExtensions.SMA(self.rsi, 3) # Creating the SMA on the RSI
       self.PlotIndicator("RSI", self.rsi, self.rsiSMA)