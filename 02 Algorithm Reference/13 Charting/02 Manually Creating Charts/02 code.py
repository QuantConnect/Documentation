# In your initialize method:
# Note - use single quotation marks: ' instead of double "
# Chart - Master Container for the Chart:
stockPlot = Chart('Trade Plot')
# On the Trade Plotter Chart we want 3 series: trades and price:
stockPlot.AddSeries(Series('Buy', SeriesType.Scatter, 0))
stockPlot.AddSeries(Series('Sell', SeriesType.Scatter, 0))
stockPlot.AddSeries(Series('Price', SeriesType.Line, 0))
self.AddChart(stockPlot)

// Later in your OnData(self, data):
self.Plot('Trade Plot', 'Price', self.lastPrice)