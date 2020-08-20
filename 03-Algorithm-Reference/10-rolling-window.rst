.. _algorithm-reference-rolling-window:

==============
Rolling Window
==============

|

Demonstration Algorithms
========================

.. list-table::
   :header-rows: 1

   * - C#
     - Python
   * - `RollingWindowAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/RollingWindowAlgorithm.cs>`_
     - `RollingWindowAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/RollingWindowAlgorithm.py>`_
   * -
     - `CustomVolatilityModelAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/CustomVolatilityModelAlgorithm.py>`_
   * - `MultipleSymbolConsolidationAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/MultipleSymbolConsolidationAlgorithm.cs>`_
     - `MultipleSymbolConsolidationAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/MultipleSymbolConsolidationAlgorithm.py>`_

|

Introduction
============

``RollingWindow`` is an array of data that allows for reverse list access semantics, where the object with index [0] refers to the *most recent item* in the window, and index [Length-1] refers to the last item in the window, where Length is the number of elements in the window.

We can store any type of object in a RollingWindow, since it is a generic type with a given max length:

.. tabs::

   .. code-tab:: c#

        closeWindow = new RollingWindow<decimal>(4);
        tradeBarWindow = new RollingWindow<TradeBar>(2);
        quoteBarWindow = new RollingWindow<QuoteBar>(2);

   .. code-tab:: py

        self.closeWindow = RollingWindow[float](4)
        self.tradeBarWindow = RollingWindow[TradeBar](2)
        self.quoteBarWindow = RollingWindow[QuoteBar](2)

These rolling arrays are updated by adding a new element of its type with the Add method:

.. tabs::

   .. code-tab:: c#

        closeWindow.Add(data["SPY"].Close);
        tradeBarWindow.Add(data["SPY"]);
        quoteBarWindow.Add(data["EURUSD"]);

   .. code-tab:: py

        self.closeWindow.Add(data["SPY"].Close)
        self.tradeBarWindow.Add(data["SPY"])
        self.quoteBarWindow.Add(data["EURUSD"])

The most recent element, the previous, and the last item for the decimal case are:

.. tabs::

   .. code-tab:: c#

        var currentClose = closeWindow[0];
        var previousClose = closeWindow[1];
        var oldestClose = closeWindow[closeWindow.Count-1];

   .. code-tab:: py

        currentClose = self.closeWindow[0]
        previousClose = self.closeWindow[1]
        oldestClose = self.closeWindow[self.closeWindow.Count-1]

We recommend using RollingWindows to hold periods of data instead of making multiple Historical Data Requests. It is much more efficient as we just need to update the RollingWindow with the latest data point, while a History call fetches the whole requested period and synchronizes the data.

.. tabs::

   .. code-tab:: c#

        // In Initialize, create the rolling windows
        public override void Initialize()
        {
            // Create a Rolling Window to keep the 4 decimal
            closeWindow = new RollingWindow<decimal>(4);
            // Create a Rolling Window to keep the 2 TradeBar
            tradeBarWindow = new RollingWindow<TradeBar>(2);
            // Create a Rolling Window to keep the 2 QuoteBar
            quoteBarWindow = new RollingWindow<QuoteBar>(2);
        }

        // In OnData, update the rolling windows
         public override void OnData(Slice data)
        {
            if(data.ContainsKey("SPY")) {
                // Add SPY bar close in the rolling window
                closeWindow.Add(data["SPY"].Close);
                // Add SPY TradeBar in rolling window
                tradeBarWindow.Add(data["SPY"]);
            }
            if(data.ContainsKey("EURUSD")) {
                // Add EURUSD QuoteBar in rolling window
                quoteBarWindow.Add(data["EURUSD"]);
            }
        }

   .. code-tab:: py

        # In Initialize, create the rolling windows
        def Initialize(self):
            # Create a Rolling Window to keep the 4 decimal
            self.closeWindow = RollingWindow[float](4)
            # Create a Rolling Window to keep the 2 TradeBar
            self.tradeBarWindow = RollingWindow[TradeBar](2)
            # Create a Rolling Window to keep the 2 QuoteBar
            self.quoteBarWindow = RollingWindow[QuoteBar](2)

        # In OnData, update the rolling windows
         def OnData(self, data):
            if data.ContainsKey("SPY"):
                # Add SPY bar close in the rolling window
                self.closeWindow.Add(data["SPY"].Close)
                # Add SPY TradeBar in rolling window
                self.tradeBarWindow.Add(data["SPY"])
            if data.ContainsKey("EURUSD"):
                # Add EURUSD QuoteBar in rolling window
                self.quoteBarWindow.Add(data["EURUSD"])

|

Combining with Indicators
=========================

A particularly common and helpful use of the RollingWindow class is to store past indicator values. The following examples create an indicator and add its values to a rolling window when the indicator is updated.

.. tabs::

   .. code-tab:: c#

        // In Initialize, create the rolling windows
        public override void Initialize()
        {
            // Creates an indicator and adds to a rolling window when it is updated
           smaWindow = new RollingWindow<IndicatorDataPoint>(5);
           SMA("SPY", 5).Updated += (sender, updated) => smaWindow.Add(updated);
        }

   .. code-tab:: py

        # In Initialize, create the rolling windows
        def Initialize(self):
            # Creates an indicator and adds to a rolling window when it is updated
            self.SMA("SPY", 5).Updated += self.SmaUpdated
            self.smaWindow = RollingWindow[IndicatorDataPoint](5)

        # Adds updated values to rolling window
        def SmaUpdated(self, sender, updated):
            self.smaWindow.Add(updated)

Indicators emit an ``Updated`` event after they have been updated. To create a rolling window of indicator points, we attach an event handler function to ``Updated``, which adds the last value of the indicator to the rolling window. The value is an ``IndicatorDataPoint`` object that represents a piece of data at a specific time.

The current (most recent) addition is stored at index 0, the previous addition to a window is at index 1, and so on until the length of the window:

.. tabs::

   .. code-tab:: c#

        var currentSma = smaWin[0];
        var previousSma = smaWin[1];
        var oldestSma = smaWin[ smaWin.Count - 1 ];

   .. code-tab:: py

        currentSma = self.smaWin[0]
        previousSma = self.smaWin[1]
        oldestSma = self.smaWin[ smaWin.Count - 1 ]