// In your initialize method:

// Chart - Master Container for the Chart:
var stockPlot = new Chart("Trade Plot");
// On the Trade Plotter Chart we want 3 series: trades and price:
var buyOrders = new Series("Buy", SeriesType.Scatter, 0);
var sellOrders = new Series("Sell", SeriesType.Scatter, 0);
var assetPrice = new Series("Price", SeriesType.Line, 0);
stockPlot.AddSeries(buyOrders);
stockPlot.AddSeries(sellOrders);
stockPlot.AddSeries(assetPrice);
AddChart(stockPlot);

// Later in your OnData(Slice data):
Plot("Trade Plot", "Price", data.Bars["SPY"].Close);