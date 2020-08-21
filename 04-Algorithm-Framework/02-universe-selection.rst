.. _algorithm-framework-universe-selection:

==================
Universe Selection
==================

|

Demonstration Algorithms
========================

.. list-table::
   :header-rows: 1

   * - C#
     - Py
   * - `Manual Selection - BasicTemplateFrameworkAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/BasicTemplateFrameworkAlgorithm.cs>`_
     - `Manual Selection - BasicTemplateFrameworkAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/BasicTemplateFrameworkAlgorithm.py>`_
   * - `Fundamental Selection - QC500UniverseSelectionModel.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Selection/QC500UniverseSelectionModel.cs>`_
     - `Fundamental Selection - QC500UniverseSelectionModel.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Selection/QC500UniverseSelectionModel.py>`_
   * - `Scheduled Selection - ScheduledUniverseSelectionModelRegressionAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/ScheduledUniverseSelectionModelRegressionAlgorithm.cs>`_.
     - `Scheduled Selection - ScheduledUniverseSelectionModelRegressionAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/ScheduledUniverseSelectionModelRegressionAlgorithm.py>`_.

|

Introduction
============
.. figure:: https://cdn.quantconnect.com/web/i/docs/algorithm-framework/universe-selection.png
   :width: 50
   :align: left

The ``Universe`` Selection Model creates Universe objects which select the assets for your strategy. We have identified three types of universes that cover most people's requirements and built helper classes to make their implementation easier. *Creating* Universe Selection Models from scratch isn't simple, so we recommend you use one of the helper universes we've provided.

To *set* a Universe Selection Model, you should use the ``AddUniverseSelection`` method in C# or the ``self.AddUniverseSelection`` method in Python. This should be done from your algorithm ``Initialize()`` method in C# or ``def Initialize()`` method in Python:

.. tabs::

   .. code-tab:: c#

        public override void Initialize()
        {
            UniverseSettings.Resolution = Resolution.Minute;
            var symbols = new [] { QuantConnect.Symbol.Create("SPY", SecurityType.Equity, Market.USA) };
            AddUniverseSelection( new ManualUniverseSelectionModel(symbols) );
        }

   .. code-tab:: py

        def Initialize(self):    # Set requested data resolution
        self.UniverseSettings.Resolution = Resolution.Minute
        symbols = [ Symbol.Create("SPY", SecurityType.Equity, Market.USA) ]
        self.AddUniverseSelection( ManualUniverseSelectionModel(symbols) )

As with all LEAN algorithms, the resolution of the assets added to the universe is configured by the ``UniverseSettings.Resolution`` property in C# or the ``self.UniverseSettings.Resolution`` property in Python.

There are 3 types of universes:

* Manual Universes - Universes which use a fixed, static set of assets.
* Fundamental Universes - Universes based on coarse price or fundamental data.
* Scheduled Universes - Universes triggering on regular intervals.

|

Manual Universe Selection
=========================
Manual universe selection chooses a static, fixed set of assets to trade. This is most useful when selecting a set of currencies or a basket of ETF stocks. You can also add assets with the traditional :ref:`AddSecurity API <algorithm-reference-initializing-algorithms-selecting-asset-data>`.

The ``ManualUniverseSelectionModel`` class is initialized with an array of ``Symbol`` objects at the UniverseSettings.Resolution data resolution. ``Symbol`` objects can be created with the Symbol.Create method.

.. tabs::

   .. code-tab:: c#

        public override void Initialize()
        {
            // Set requested data resolution
            UniverseSettings.Resolution = Resolution.Minute;
            AddUniverseSelection(
                new ManualUniverseSelectionModel(
                    QuantConnect.Symbol.Create("SPY", SecurityType.Equity, Market.USA)
            ));
        }

   .. code-tab:: py

        def Initialize(self):
            self.UniverseSettings.Resolution = Resolution.Minute
            symbols = [ Symbol.Create("SPY", SecurityType.Equity, Market.USA) ]
            self.AddUniverseSelection(ManualUniverseSelectionModel(symbols))

|

Fundamental Universe Selection
==============================
Framework Universe Selection models can use the same function-based selection mechanics as other algorithms. QuantConnect provides two helper methods for these universes which handle the framework requirements: ``CoarseFundamentalUniverseSelectionModel`` and the ``FineFundamentalUniverseSelectionModel``.

To define a fundamental Universe Selection model, you need to create an instance of the class and set with the ``AddUniverseSelection`` method:

.. tabs::

   .. code-tab:: c#

        // Setting Universe Model in QCAlgorithm
        public override void Initialize()
        {
        AddUniverseSelection(new FineFundamentalUniverseSelectionModel(SelectCoarse, SelectFine));
        }

        IEnumerable<Symbol> SelectCoarse(IEnumerable<CoarseFundamental> coarse)
        {
            var tickers = new[] { "AAPL", "AIG", "IBM" };
            return tickers.Select(x =>
                QuantConnect.Symbol.Create(x, SecurityType.Equity, Market.USA)
            );
        }

        IEnumerable<Symbol> SelectFine(IEnumerable<FineFundamental> fine)
        {
            return fine.Select(f => f.Symbol);
        }

   .. code-tab:: py

        # Setting Universe Model in QCAlgorithm
        def Initialize(self):
            self.AddUniverseSelection(
                FineFundamentalUniverseSelectionModel(self.SelectCoarse, self.SelectFine)
            )

        def SelectCoarse(self, coarse):
            tickers = ["AAPL", "AIG", "IBM"]
            return [Symbol.Create(x, SecurityType.Equity, Market.USA) for x in tickers]

        def SelectFine(self, fine):
            return [f.Symbol for f in fine]

The fundamental universes perform the same filtering as the traditional algorithm explained in the :ref:`Universe <algorithm-reference-universes>` section. The Coarse selection function is passed a list of ``CoarseFundamental`` objects and should return a list of Symbol objects. The Fine selection function is passed a subset of ``FineFundamental`` objects generated from coarse selection results and should return a list of Symbol objects. See the Universe section for more information on these filtering functions.

|

Scheduled Universe Selection
============================
Scheduled universes allow you to perform universe selection at fixed, regular intervals. In live trading this might be applied to fetching tickers from Dropbox, or performing analysis on historical data and choosing resulting symbols. The class for creating scheduled universes is called ``ScheduledUniverseSelectionModel``.

.. tabs::

   .. code-tab:: c#

        public ScheduledUniverseSelectionModel(
            DateRule dateRule,
            TimeRule timeRule,
            Func<DateTime, IEnumerable<Symbol>> selector,
            UniverseSettings settings = null,
            ISecurityInitializer initializer = null
        )

   .. code-tab:: py

        ScheduledUniverseSelectionModel(dateRule, timeRule, selector,  universeSettings=null, securityInitializer=null)

The universe selection helper works in the same way as the :ref:`Scheduled Event API <algorithm-reference-scheduled-events>` requiring a ``DateRule``, a ``TimeRule`` to set the callback times, and a function to execute, which returns a list of Symbol objects.

.. tabs::

   .. code-tab:: c#

        // Selection will run on mon/tues/thurs at 00:00/06:00/12:00/18:00
        AddUniverseSelection(new ScheduledUniverseSelectionModel(
            DateRules.Every(DayOfWeek.Monday, DayOfWeek.Tuesday, DayOfWeek.Thursday),
            TimeRules.Every(TimeSpan.FromHours(6)),
            SelectSymbols // selection function in algorithm.
        ));

        // Create selection function which returns symbol objects.
        IEnumerable<Symbol> SelectSymbols(DateTime dateTime)
        {
            return new[]
            {
                Symbol.Create("SPY", SecurityType.Equity, Market.USA),
                Symbol.Create("AAPL", SecurityType.Equity, Market.USA),
                Symbol.Create("IBM", SecurityType.Equity, Market.USA)
            }
        }

   .. code-tab:: py

        # Selection will run on mon/tues/thurs at 00:00/06:00/12:00/18:00
        self.AddUniverseSelection(ScheduledUniverseSelectionModel(
            self.DateRules.Every(DayOfWeek.Monday, DayOfWeek.Tuesday, DayOfWeek.Thursday),
            self.TimeRules.Every(timedelta(hours = 12)),
            self.SelectSymbols # selection function in algorithm.
            ))

        # Create selection function which returns symbol objects.
        def SelectSymbols(self, dateTime):
            symbols = []
            symbols.append(Symbol.Create('SPY', SecurityType.Equity, Market.USA))
            return symbols

|

Creating Universe Models
========================
Universe Models must implement a ``IUniverseSelectionModel`` interface. It has one method, ``CreateUniverses(QCAlgorithm algorithm)``. The algorithm object is passed into the method to give you access to the QuantConnect API, and it should return an array of Universe objects.

.. code-block::

    // Algorithm framework model that defines the universes to be used by an algorithm
    interface IUniverseSelectionModel
    {
        // Creates the universes for this algorithm, called once after IAlgorithm.Initialize
        IEnumerable<Universe> CreateUniverses(QCAlgorithmFramework algorithm);
    }

Generally, you should be able to extend one of the universes described above, so if you ever find yourself needing to do something that doesn't fit into the categories above, please let us know, and we'll create a new foundational type of universe model.

|

Configuring Securities
======================
To configure securities in a universe, you should use the ``SetSecurityInitializer()`` method. Call this from your Initialize method and set an ``ISecurityInitializer`` class, or use the functional implementation demonstrated below for simple requests. This feature is described in detail in the :ref:`Configuring Universe Securities <algorithm-reference-universes-configuring-universe-securities>` section.

.. tabs::

   .. code-tab:: c#

        //Most common request; requesting raw prices for universe securities.
        SetSecurityInitializer(x => x.SetDataNormalizationMode(DataNormalizationMode.Raw));

   .. code-tab:: py

        # Most common request; requesting raw prices for universe securities.
        self.SetSecurityInitializer(lambda x: x.SetDataNormalizationMode(DataNormalizationMode.Raw))