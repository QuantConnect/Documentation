# In your initialize method:
# Note - use single quotation marks: ' instead of double "
# Chart - Master Container for the Chart:
stockPlot = Chart('Trade Plot')
# On the Trade Plotter Chart we want 3 series: trades and price:
stockPlot.AddSeries(Series('Buy', SeriesType.Scatter, 0))
stockPlot.AddSeries(Series('Sell', SeriesType.Scatter, 0))
stockPlot.AddSeries(Series('Price', SeriesType.Line, 0))
# Or using custom chart
# Import the necessary module before using Custom color
from System.Drawing import Color
stockPlot.AddSeries(Series('Price', SeriesType.Line, '$', Color.Green))
stockPlot.AddSeries(Series('Buy', SeriesType.Scatter, '$', Color.Red, ScatterMarkerSymbol.Triangle))
stockPlot.AddSeries(Series('Sell', SeriesType.Scatter, '$', Color.Blue, ScatterMarkerSymbol.TriangleDown))
self.AddChart(stockPlot)

# Later in your OnData(self, data):
self.Plot('Trade Plot', 'Price', self.lastPrice)
