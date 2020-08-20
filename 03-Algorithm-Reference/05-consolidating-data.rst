.. _algorithm-reference-consolidating-data:

========================================
Algorithm Reference - Consolidating Data
========================================

|

Demonstration Algorithms
========================

.. list-table::
   :header-rows: 1

   * - C#
     - Python
   * - `DataConsolidationAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/DataConsolidationAlgorithm.cs>`_
     - `DataConsolidationAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/DataConsolidationAlgorithm.py>`_
   * - `MultipleSymbolConsolidationAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/MultipleSymbolConsolidationAlgorithm.cs>`_
     - `MultipleSymbolConsolidationAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/MultipleSymbolConsolidationAlgorithm.py>`_
   * - `BasicTemplateFuturesConsolidationAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/BasicTemplateFuturesConsolidationAlgorithm.cs>`_
     - `BasicTemplateFuturesConsolidationAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/BasicTemplateFuturesConsolidationAlgorithm.py>`_
   * - `RenkoConsolidatorAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/RenkoConsolidatorAlgorithm.cs>`_
     -

Video Tutorials
===============

.. list-table::
   :header-rows: 1

   * - C#
     - Python
   * - `Quick Start Lesson 4: How do I use Consolidators? <https://www.youtube.com/watch?v=bbZy__qd1aA>`_
     - `Opening Range Breakout - Time Period Consolidation <https://www.youtube.com/watch?v=VDzmXBdBW3g&list=PLD7-B3LE6mz5jsEb127kdyJVMJrBNfbmI&index=5>`_

|

Introduction
============

Consolidating data allows you to create bars of any length from smaller bars. Commonly this is used to combine one-minute price bars into longer bars such as 10-20 minute bars. Using the price movement over a longer period can sometimes reduce the noise of the markets to make trading more efficient.

To achieve this, QuantConnect allows you to create ``Consolidator`` objects and register them for data. Using a consolidator makes creating longer periods easier and reduces the chance of bugs. In the following sections, we will introduce the different types of consolidators and show you how to shape data into any form.

|

Data Shapes and Sizes
=====================

Consolidated output data is in the same format as the input. :ref:`Small TradeBars <algorithm-reference-handling-data-tradebars>` are aggregated into large TradeBars, and small :ref:`QuoteBars <algorithm-reference-handling-data-quotebars>` are aggregated into large QuoteBars.

.. figure:: http://cdn.quantconnect.com/docs/i/consolidators-small-to-large.png

Many asset-types in QuantConnect have data for both trades and quotes. In this case, we default to generating bars for the TradeBar data source. Forex is the exception here, which only has data for quotes, and we so default to generating QuoteBars.

|

Consolidating Periods
=====================

The most common request is to create bars based on a period of time. QuantConnect has created a helper for this called ``Consolidate()``. The consolidate method looks up the Symbol requested, creates a consolidator for the given period, and passes the output to the provided function event handler. With just one line of code, you can create data in any format required. The consolidate helper accepts a ``timedelta``, ``Resolution``, or ``Calendar`` period specifier:

.. tabs::

   .. code-tab:: c#

        // Consolidate 1min SPY -> 45min Bars
        Consolidate("SPY", TimeSpan.FromMinutes(45), FortyFiveMinuteBarHandler)

        // Consolidate 1min SPY -> 1-Hour Bars
        Consolidate("SPY", Resolution.Hour, HourBarHandler)

        // Consolidate 1min SPY -> 1-Week Bars
        Consolidate("SPY", Calendar.Weekly, WeekBarHandler)

   .. code-tab:: py

        # Consolidate 1min SPY -> 45min Bars
        self.Consolidate("SPY", timedelta(minutes=45), self.FortyFiveMinuteBarHandler)

        # Consolidate 1min SPY -> 1-Hour Bars
        self.Consolidate("SPY", Resolution.Hour, self.HourBarHandler)

        # Consolidate 1min SPY -> 1-Week Bars
        self.Consolidate("SPY", Calendar.Weekly, self.WeekBarHandler)

The event handler function of Consolidate accepts one argument, the resulting bar. For most data sources, this defaults to TradeBar format. For a full example, see the `demonstration algorithm <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/DataConsolidationAlgorithm.cs#L94>`_.

.. tabs::

   .. code-tab:: c#

        // Example event handler from Consolidate helper.
        void FortyFiveMinuteBarHandler(TradeBar consolidated) {
            Log($"{consolidated.EndTime:o} 45 minute consolidated.");
        }

   .. code-tab:: py

        # Example event handler from Consolidate helper.
        def FortyFiveMinuteBarHandler(self, consolidated):
              self.Log(f"{consolidated.EndTime} >> FortyFiveMinuteBarHandler >> {consolidated.Close}")

Most Common Error: Adding Braces
--------------------------------

The most common error is to put braces "``()``" at the end of your function call when defining the event handler. Using braces causes the method to be executed, and the result passed in as the event handler. Remember to simply pass the name of your function to the event system. i.e. It should be ``self.EventHandler`` not ``self.EventHandler()``.

|

Consolidating Data for Indicators
=================================

Consolidated data can easily be used with indicators along the period-resolution boundaries. This is possible with one line of code by the basic indicator API, as shown below. Using these helper methods, the required consolidators are created, and the output bar is automatically used to update the indicator. See the :ref:`Indicators <algorithm-reference-indicators>` documentation for more information.

.. tabs::

   .. code-tab:: c#

        // Consolidating minute SPY into 14-bar daily indicators
        var ema = EMA("SPY", 14, Resolution.Daily);
        var sma = SMA("SPY", 14, Resolution.Daily);

   .. code-tab:: py

        # Consolidating minute SPY into 14-bar daily indicators
        ema = self.EMA("SPY", 14, Resolution.Daily)
        sma = self.SMA("SPY", 14, Resolution.Daily)

A common request is to use consolidators with indicators to create indicators with exotic data (e.g. 35-minute EMA). To do this, you will need to create the indicator and register it to receive updates. This is done with the ``RegisterIndicator`` function. Registering the indicator wires it up to get data updates from LEAN automatically.

.. tabs::

   .. code-tab:: c#

        // Generate 7 minute bars; then SMA-10 generates the average of last 10 bars.
        AddEquity("SPY", Resolution.Minute);
        var sma = new SimpleMovingAverage(10);
        RegisterIndicator("SPY", sma, TimeSpan.FromMinutes(7));

   .. code-tab:: py

        # Generate 7 minute bars; then SMA-10 generates the average of last 10 bars.
        self.AddEquity("SPY", Resolution.Minute)
        self.sma = SimpleMovingAverage(10)
        self.RegisterIndicator("SPY", self.sma, timedelta(minutes=7))

|

Rolling Window of Consolidated Bars
===================================

A common request is to compare a current consolidated bar with one from the past. This can be achieved by combining a `RollingWindow <algorithm-reference-rolling-window>` with a Consolidator. This is easy to achieve with the individual tools provided here and in the RollingWindow documentation. First, you must create a consolidator for the data you need, and then you must add it to the rolling window in the event handler. Building this will allow you to easily compare recent custom-bars with previous ones created.

.. tabs::

   .. code-tab:: c#

        // In initialize create a consolidator and add its bars to the window
        _window = new RollingWindow<TradeBar>(2);
        Consolidate("SPY", TimeSpan.FromMinutes(45), x => _window.Add(x));

        // Now you can use the bar history; _window[0] is current, _window[1] is previous bar.
        if (_window.IsReady && _window[0].Close > _window[1].Close) {
             Log("Current close price higher than the one 45 minutes ago");
        }

   .. code-tab:: py

        # In initialize create a consolidator and add its bars to the window
        self.window = RollingWindow[TradeBar](2)
        self.Consolidate("SPY", timedelta(minutes=45), lambda x: self.window.Add(x))

        # Now you can use the bar history; window[0] is current, window[1] is previous bar.
        if self.window.IsReady and window[0].Close > window[1].Close:
             self.Log("Current close price higher than the one 45 minutes ago")

|

Manually Consolidating Bar Count
================================

You can consolidate a certain number of bars or ticks using the count constructor of the consolidators. It will have the effect of joining n-bars together. To do this, you must create a manual consolidator and register it to receive data. The output of the consolidated bars will be piped to an event handler.

.. tabs::

   .. code-tab:: c#

        public override void Initialize()
        {
            AddEquity("QQQ", Resolution.Hour);
            var threeCountConsolidator = new TradeBarConsolidator(3);
            threeCountConsolidator.DataConsolidated += ThreeBarHandler;
            SubscriptionManager.AddConsolidator("QQQ", threeCountConsolidator);
        }

        private void ThreeBarHandler(object sender, TradeBar bar) {
            // With hourly data the bar period is 3-hours
            Debug((bar.EndTime - bar.Time).ToString() + " " + bar.ToString());
        }

   .. code-tab:: py

        def Initialize(self):
            self.AddEquity("QQQ", Resolution.Hour)
            threeCountConsolidator = TradeBarConsolidator(3)
            threeCountConsolidator.DataConsolidated += self.ThreeBarHandler
            self.SubscriptionManager.AddConsolidator("QQQ", threeCountConsolidator)

        def ThreeBarHandler(self, sender, bar):
            # With hourly data the bar period is 3-hours
            self.Debug(str(bar.EndTime - bar.Time) + " " + bar.ToString())

Most people will not need to manually consolidate data, but if needed this gives you more control over the objects performing the aggregation and the data being used to feed them.

|

Manually Consolidating Periods
==============================

Data can be aggregated according to a period, with the time of the bars used to perform the consolidation. This requires the input data to be of a higher resolution than the desired consolidation period, e.g. to build a 1.5 hour bar you need minute data.

The mechanics are identical to consolidation counts described previously. You must create a consolidator object and then register it to receive data with the Subscription Manager.

.. tabs::

   .. code-tab:: c#

        public override void Initialize()
        {
             // Make sure you have the data you need
            AddEquity("QQQ", Resolution.Minute);

            // Create consolidator you need and attach event handler
            var thirtyMinuteConsolidator = new TradeBarConsolidator(TimeSpan.FromMinutes(30));
            thirtyMinuteConsolidator.DataConsolidated += ThirtyMinuteHandler;

            // Register consolidator to get automatically updated with minute data
            SubscriptionManager.AddConsolidator("QQQ", thirtyMinuteConsolidator);
        }

        private void ThirtyMinuteHandler(object sender, TradeBar bar) {
            // Bar period is 30 min from the consolidator above.
            Debug((bar.EndTime - bar.Time).ToString() + " " + bar.ToString());
        }

   .. code-tab:: py

        def Initialize(self):
            # Make sure you have the data you need
            self.AddEquity("QQQ", Resolution.Minute)

            # Create consolidator you need and attach event handler
            thirtyMinuteConsolidator = TradeBarConsolidator(timedelta(minutes=30))
            thirtyMinuteConsolidator.DataConsolidated += self.ThirtyMinuteHandler

            # Register consolidator to get automatically updated with minute data
            self.SubscriptionManager.AddConsolidator("QQQ", thirtyMinuteConsolidator)

        def ThirtyMinuteHandler(self, sender, bar):
            # Bar period is now 30 min from the consolidator above.
            self.Debug(str(bar.EndTime - bar.Time) + " " + bar.ToString())

|

Renko Bar Consolidation
=======================

Renko bars are the consolidation of fixed price movements instead of fixed time periods. When you define a ``RenkoConsolidator`` you set the price movement instead of the period of the consolidation.

.. tabs::

   .. code-tab:: c#

        // Create Renko consolidator to trigger event when price moves $2.50
        var renkoClose = new RenkoConsolidator(2.5m);
        renkoClose.DataConsolidated += HandleRenkoClose;

        // Register the consolidator for data
        SubscriptionManager.AddConsolidator("SPY", renkoClose);

   .. code-tab:: py

        # Create Renko consolidator to trigger event when price moves $2.50
        renkoClose = RenkoConsolidator(2.5)
        renkoClose.DataConsolidated += self.HandleRenkoClose

        # Register the consolidator for data.
        self.SubscriptionManager.AddConsolidator("SPY", renkoClose)

You can see a full example of a renko consolidation in the `demonstration algorithm <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/RenkoConsolidatorAlgorithm.py>`_.

|

Data Consolidation Events
=========================

The result of the consolidation is passed into an *event handler*. An event handler is a function in your algorithm designed to receive the bar. It can have any name but must have the required parameters. Depending on how you're using the consolidator system, you must use one of the method patterns below:


.. tabs::

   .. code-tab:: c#

        // self.Consolidate() Event Handler
        void FortyFiveMinuteBarHandler(TradeBar consolidated) {
        }

        // Manually Created Event Handler
        void ThirtyMinuteBarHandler(object sender, TradeBar consolidated) {
        }

   .. code-tab:: py

        # self.Consolidate() Event Handler
        def FortyFiveMinuteBarHandler(self, consolidated):
              pass

        # Manually Created Event Handler
        def ThirtyMinuteBarHandler(self, sender, consolidated):
              pass

|

Removing a Consolidator
=======================

If you manually create a consolidator for a universe subscription, you should remember to remove it again later once the security leaves your universe. If you do not "tidy up", these can compound internally, causing your algorithm to slow down and eventually die once it runs out of RAM.

You will need to save a reference to the consolidator to remove it cleanly. We recommend using a class to organize all of the symbol-specific objects created over the lifetime of a security in your universe. See this `example <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/Alphas/GasAndCrudeOilEnergyCorrelationAlpha.py#L189>`_ Alpha as an example of removing consolidators from universe subscriptions.

.. tabs::

   .. code-tab:: c#

        // Remove a consolidator instance from subscription manager
        algorithm.SubscriptionManager.RemoveConsolidator(symbol, myConsolidator)

   .. code-tab:: py

        # Remove a consolidator instance from subscription manager
        algorithm.SubscriptionManager.RemoveConsolidator(self.symbol, self.myConsolidator)