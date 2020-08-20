.. _algorithm-reference-charting:

==============================
Algorithm Reference - Charting
==============================

|

Introduction
============

We provide a powerful charting API which can be used to build many chart types. At its simplest it can be used with a single line of code:

.. tabs::

   .. code-tab:: c#

        Plot("Series Name", value);

   .. code-tab:: py

        self.Plot("Series Name", value)

With this code, a line graph is added underneath your *Strategy Equity* chart, and your requested values are displayed over time. To create a new chart (new tab), you should also specify the chart name in your request:

.. tabs::

   .. code-tab:: c#

        private MovingAverageConvergenceDivergence _macd
        // In Initialize()
        _macd = MACD（"SPY", 12, 26,9, MovingAverageType.Exponential, Resolution.Daily）
        // In OnData()
        Plot("My Indicators", "MACD Signal", _macd.Signal);

   .. code-tab:: py

        # In Initialize(self)
        self.macd = MACD（"SPY", 12, 26,9, MovingAverageType.Exponential, Resolution.Daily）
        # In OnData(self, data)
        self.Plot("My Indicators", "MACD Signal", self.macd.Signal)

Behind the scenes, these methods create a new ``Chart`` object and add it to your algorithm, and then add a new ``Series`` object to that chart. A chart is made from many series. You can also initialize your charts manually to get more control over their look and feel.

|

Manually Creating Charts
========================

In your initialize method, you can use the ``AddChart(Chart obj)`` method to insert a new chart. Each chart object has an internal collection of Series objects.

In creating Series objects, you must specify the name of the series, the `SeriesType <https://www.quantconnect.com/lean/docs#>`_, and the index the series operates on. The series index refers to its position in the chart - for example, if all the series are index 0, they will lay on top of each other. If each series has its own index, it will have several mini-charts stacked next to each other.

The picture below shows an EMA cross chart with both EMA series set to the same index:

.. figure:: https://cdn.quantconnect.com/web/i/docs/charting-overlay.png

Using different indexes the chart looks as follows:

.. figure:: https://cdn.quantconnect.com/web/i/docs/charting-stacked.png

.. tabs::

   .. code-tab:: c#

        // In your initialize method:

        // Chart - Master Container for the Chart:
        var stockPlot = new Chart("Trade Plot");
        // On the Trade Plotter Chart we want 3 series: trades and price:
        var buyOrders = new Series("Buy", SeriesType.Scatter, 0);
        var sellOrders = new Series("Sell", SeriesType.Scatter, 0);
        var assetPrice = new Series("Price", SeriesType.Line, 0);
        // Or Using Custom Chart
        // Import the necessary module before using Custom color
        using System.Drawing;
        var buyOrders = new Series("Buy", SeriesType.Scatter, "$", Color.Red, ScatterMarkerSymbol.Triangle);
        var sellOrders = new Series("Sell", SeriesType.Scatter, "$", Color.Blue, ScatterMarkerSymbol.TriangleDown);
        var assetPrice = new Series("Price", SeriesType.Line, "$", Color.Green);
        stockPlot.AddSeries(buyOrders);
        stockPlot.AddSeries(sellOrders);
        stockPlot.AddSeries(assetPrice);
        AddChart(stockPlot);

        // Later in your OnData(Slice data):
        Plot("Trade Plot", "Price", data.Bars["SPY"].Close);

   .. code-tab:: py

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
        self.Plot('Trade Plot', 'Price', data.Bars["SPY"].Close)

|

Supported Series Types
======================

The charting API supports the following series types. Nothing special is required to use these series; simply specify them for your series when creating your chart.

.. code-block::

       SeriesType.Line
             .Scatter
             .Candle
             .Bar
             .Flag

|

Custom Colors and Scatter Symbols
=================================

You can customize the chart color and the scatter symbol when creating the series. In your customized chart, instead of specifying the index, you need to specify the label name of the y-axis using a string like ``'$'``, ``'%'``, or an empty string ``''``. The chart color can be changed by specifying the parameter ``"Color.ColorName"``.

You can also change the marker symbol of the scatter plot. The charting API supports the following scatter marker symbol types.

.. code-block::

       ScatterMarkerSymbol.Circle
                      .Diamond
                      .Square
                      .Triangle
                      .TriangleDown

|

Charting Limitations
====================

Intensive charting generates hundreds of megabytes (200MB) of data, which is far too much to stream online or display in a web browser. Because of this, we limit the number of points a chart can have to 4000. If you see the error ``Exceeded maximum points per chart``, data skipped, then you have hit this limit and should reduce your sampling frequency.