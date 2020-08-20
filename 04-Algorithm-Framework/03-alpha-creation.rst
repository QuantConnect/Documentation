.. _algorithm-framework-alpha-creation:

==============
Alpha Creation
==============

|

.. list-table:: Demonstration Algorithms
   :header-rows: 1

   * - C#
     - Python
   * - `ConstantAlphaModel.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Alphas/ConstantAlphaModel.cs>`_
     - `ConstantAlphaModel.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Alphas/ConstantAlphaModel.py>`_
   * - `Indicator Based Alpha Model - EmaCrossAlphaModel.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Alphas/EmaCrossAlphaModel.cs>`_
     - `Indicator Based Alpha Model - EmaCrossAlphaModel.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Alphas/EmaCrossAlphaModel.py>`_
   * - `Indicator Based Alpha Model - MacdAlphaModel.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Alphas/MacdAlphaModel.cs>`_
     - `Indicator Based Alpha Model - MacdAlphaModel.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Alphas/MacdAlphaModel.py>`_
   * - `Indicator Based AlphaModel - RsiAlphaModel.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Alphas/RsiAlphaModel.cs>`_
     - `Indicator Based AlphaModel - RsiAlphaModel.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Alphas/RsiAlphaModel.py>`_
   * - `Grouped Insights Alpha Model - PearsonCorrelationPairsTradingAlphaModel.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Alphas/PearsonCorrelationPairsTradingAlphaModel.cs>`_
     - `Grouped Insights Alpha Model - PearsonCorrelationPairsTradingAlphaModel.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Alphas/PearsonCorrelationPairsTradingAlphaModel.py>`_
   * - `Magnitude Prediction Alpha Model - HistoricalReturnsAlphaModel.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Alphas/HistoricalReturnsAlphaModel.cs>`_
     - `Magnitude Prediction Alpha Model - HistoricalReturnsAlphaModel.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Alphas/HistoricalReturnsAlphaModel.py>`_

|

Introduction
============

.. figure:: https://cdn.quantconnect.com/web/i/docs/algorithm-framework/alpha-creation.png

The Alpha Model is primarily concerned with predicting market trends and signalling to the algorithm the best moments for making an investment. These signals, or ``Insight`` objects, contain the Direction, Magnitude, and Confidence of a market prediction. Insights should be generated on a defined set of assets provided by the Universe Selection Model and only emitted when they change.

To set an Alpha Model, you can use the self.AddAlpha(alpha) method in Python, or the AddAlpha(alpha) method in C#.. This should be done from your algorithm def Initialize() method.

.. tabs::

   .. code-tab:: c#

        public override void Initialize()
        {
            // Initialize Framework with simplest possible alpha model - constant up signal.
            AddAlpha( new ConstantAlphaModel(InsightType.Price, InsightDirection.Up, TimeSpan.FromMinutes(20), 0.025, null) );
        }

   .. code-tab:: py

        def Initialize(self):
            # Initialize Framework with simplest possible alpha model - constant up signal.
            self.AddAlpha(ConstantAlphaModel(InsightType.Price, InsightDirection.Up, timedelta(minutes = 20), 0.025, None))

|

Alpha Model Structure
=====================
Alpha models are intended to be easy to create and the primary location for iterating your strategy. To create Alpha Models, you must implement the ``IAlphaModel`` interface as shown in the examples below. This has two methods:

#. ``Update()`` - Generate Insight objects.
#. ``OnSecuritiesChanged()`` - Receive notifications about security changes.

.. tabs::

   .. code-tab:: c#

        // Algorithm framework model that produces insights
        class MyAlphaModel : IAlphaModel, INotifiedSecurityChanges
        {
            // Updates this alpha model with the latest data from the algorithm.
            // This is called each time the algorithm receives data for subscribed securities
            Insight[] Update(QCAlgorithm algorithm, Slice data) {
                // Generate insights on the securities in universe.
            }

            void OnSecuritiesChanged(QCAlgorithm algorithm, SecurityChanges changes) {
                // Handle security changes in from your universe model.
            }
        }

   .. code-tab:: py

        # Algorithm framework model that produces insights
        class MyAlphaModel:

            def Update(self, algorithm, slice):
                # Updates this alpha model with the latest data from the algorithm.
                # This is called each time the algorithm receives data for subscribed securities
                # Generate insights on the securities in the universe.
                insights = []
                return insights

            def OnSecuritiesChanged(self, algorithm, changes):
                # Handle security changes in from your universe model.

You can access all the normal QCAlgorithm messages such as ``Log``, and ``Debug`` from the algorithm object; for example logging is located at ``algorithm.Log("message")``. The ``slice`` parameter contains the latest data available to the algorithm.

|

Creating Insights
=================
The Update method returns an array of Insight objects. An Insight is a *single* prediction for an asset. These can be thought of as actionable trading signals, indicating the asset direction, magnitude, and confidence in the near future. All insights can take a weight parameter to set the desired weighting for the insight. Insight classes have the following important properties:

.. tabs::

   .. code-tab:: c#

        class Insight {
            // Symbol of this Insight
            Symbol Symbol;

            // Gets the type of insight, for example, price insight or volatility insight
            InsightType Type;

            // Gets the predicted direction, Down, Flat or Up.
            InsightDirection Direction;

            // Gets the period over which this insight is expected to come to fruition
            TimeSpan Period;

            // Gets the predicted percent change in the insight type (price/volatility) (optional)
            double? Magnitude;

            // Gets the confidence in this insight (optional)
            double? Confidence;

            // The weight in this insight (optional)
            double? Weight;
        }

   .. code-tab:: py

        class Insight:
            self.Symbol # Symbol of this Insight
            self.Type # Type of insight (price or volatility)
            self.Direction # Insight Direction (down, flat or up)
            self.Period # Insight period (TimeSpan)
            self.Magnitude # Expected percent change (optional, double)
            self.Confidence # Confidence in insight (optional, double)
            self.Weight # Weighting of the insight (optional, double)

An ``Insight`` constructor takes the following arguments:

.. tabs::

   .. code-tab:: c#

        // Insight Constructor Arguments
        // new Insight(symbol, period, type, confidence=null, magnitude=null, source=null, weighting=null);
        var insight = new Insight("IBM", TimeSpan.FromMinutes(20), InsightType.Price, InsightDirection.Up, null, weight:0.1);

   .. code-tab:: py

        # Insight Constructor Arguments:
        # Insight(symbol, timedelta, type, direction, magnitude=None, confidence=None, sourceModel=None)
        Insight("IBM", timedelta(minutes=20), InsightType.Price, InsightDirection.Up, 0.0025, 1.00, None, 0.1)


We have provided a helper method to make creating Insights easier. This can be used in your Update method to create insight objects for your Alpha Model of the Price type:

.. tabs::

   .. code-tab:: c#

        var insight = Insight.Price("IBM", TimeSpan.FromMinutes(20), InsightDirection.Up);

   .. code-tab:: py

        insight = Insight.Price("IBM", timedelta(minutes = 20), InsightDirection.Up)

If you are creating a portfolio style of algorithm where the Insights can recommend a specific weighting, you can specify the "Weight" property, which most portfolio construction systems will allocate capital to accordingly.

.. tabs::

   .. code-tab:: c#

        // Skipping magnitude, confidence and source model and assigning 25% to weighting.
        var insight = Insight.Price("IBM", TimeSpan.FromMinutes(20), InsightDirection.Up, null, null, null, 0.25);

   .. code-tab:: py

        # Skipping magnitude, confidence and source model and assigning 25% to weighting.
        insight = Insight.Price("IBM", timedelta(minutes = 20), InsightDirection.Up, None, None, None, 0.25)

|

Grouped Insights
================
Sometimes an algorithm's performance relies on multiple insights being traded together - such as pairs trading and an options straddle. These insights should be *grouped*. Insight groups signal to the execution models that the insights need to be acted on as a single unit to maximize the alpha created.

When you return the grouped insights from your Alpha Model, simply use the ``Insight.Group()`` helper method to mark the insights as a set.

.. tabs::

   .. code-tab:: c#

         // Insight helper for grouping insights together
        return Insight.Group(insight1, insight2, insight3);

   .. code-tab:: py

        # Insight helper for grouping insights together
        return Insight.Group( [ insight1, insight2, insight3 ] )

|

.. _algorithm-framework-alpha-creation-multi-alpha-algorithms:

Multi-Alpha Algorithms
======================
The algorithm framework allows adding multiple alpha classes to your algorithm and generates Insights on all of them. The combined stream of Insights is then passed to the Portfolio Construction model. Nothing special is required to achieve this - simply use the ``AddAlpha()`` methods to add each one. Below is an example of combining two Alpha Models to be used in one algorithm:

.. tabs::

   .. code-tab:: c#

        // Define alpha model as a composite of the rsi and ema cross models
        AddAlpha( new RsiAlphaModel() );
        AddAlpha( new EmaCrossAlphaModel() );

   .. code-tab:: py

        # Define alpha model as a composite of the rsi and ema cross models
        self.AddAlpha( RsiAlphaModel() )
        self.AddAlpha( EmaCrossAlphaModel() )

As many Alpha Models as required can be added to the algorithm. Each Alpha Model has a unique name, and the Insights generated are automatically named according to the source Alpha Model which created it.

|

Good Design Patterns
====================
To make Alpha Models as useful and pluggable as possible, we recommend you follow the following design suggestions. These will ensure you can quickly migrate the Alpha from one algorithm to another if ever needed.

1. Use Assets Defined By Universe Selection Model

The `Universe Selection Model <https://www.quantconnect.com/docs/algorithm-framework/universe-selection>`_ is in charge of selecting assets, so you should not assume any fixed set of assets. When assets are added to your universe, they will trigger an ``OnSecuritiesChanged()`` event. From there, you can initialize any state or history required for your Alpha Model.

.. tabs::

   .. code-tab:: c#

        // Event fired each time the we add/remove securities from the data feed
        public void OnSecuritiesChanged(QCAlgorithmFramework algorithm, SecurityChanges changes)
        {
            foreach (var added in changes.AddedSecurities)
            {
                SymbolData symbolData;
                if (!_symbolDataBySymbol.TryGetValue(added.Symbol, out symbolData))
                {
                    // create fast/slow EMAs
                    var fast = algorithm.EMA(added.Symbol, _fastPeriod);
                    var slow = algorithm.EMA(added.Symbol, _slowPeriod);
                    _symbolDataBySymbol[added.Symbol] = new SymbolData
                    {
                        Security = added,
                        Fast = fast,
                        Slow = slow
                    };
                }
                else
                {
                    // a security that was already initialized was re-added, reset the indicators
                    symbolData.Fast.Reset();
                    symbolData.Slow.Reset();
                }
            }
        }

        // Contains data specific to a symbol required by this model
        private class SymbolData
        {
            public Security Security { get; set; }
            public Symbol Symbol => Security.Symbol;
            public ExponentialMovingAverage Fast { get; set; }
            public ExponentialMovingAverage Slow { get; set; }
            public bool FastIsOverSlow { get; set; }
            public bool SlowIsOverFast => !FastIsOverSlow;
        }

   .. code-tab:: py

            def OnSecuritiesChanged(self, algorithm, changes):
                '''Event fired each time the we add/remove securities from the data feed
                Args:
                    algorithm: The algorithm instance that experienced the change in securities
                    changes: The security additions and removals from the algorithm'''
                for added in changes.AddedSecurities:
                    symbolData = self.symbolDataBySymbol.get(added.Symbol)
                    if symbolData is None:
                        # create fast/slow EMAs
                        symbolData = SymbolData(added)
                        symbolData.Fast = algorithm.EMA(added.Symbol, self.fastPeriod)
                        symbolData.Slow = algorithm.EMA(added.Symbol, self.slowPeriod)
                        self.symbolDataBySymbol[added.Symbol] = symbolData
                    else:
                        # a security that was already initialized was re-added, reset the indicators
                        symbolData.Fast.Reset()
                        symbolData.Slow.Reset()

        class SymbolData:
            '''Contains data specific to a symbol required by this model'''
            def __init__(self, security):
                self.Security = security
                self.Symbol = security.Symbol
                self.Fast = None
                self.Slow = None
                self.FastIsOverSlow = False

            @property
            def SlowIsOverFast(self):
                return not self.FastIsOverSlow

2. Give Alpha Models A Unique Name

To ensure your Alpha Model can be used by all `Portfolio Construction Models <https://www.quantconnect.com/docs/algorithm-framework/portfolio-construction>`_, you should assign a unique name to your Alpha Model. Some Portfolio Construction Models can combine multiple Alpha Models together, and it can be important to distinguish between them. By default, we use the class-type name as the Alpha Model name.

.. tabs::

   .. code-tab:: c#

        public class RsiAlphaModel : AlphaModel
        {
            // Give your alpha a name (perhaps based on its constructor args?)
            public override string Name { get; }
        }

   .. code-tab:: py

        class RsiAlphaModel(AlphaModel):
            self.Name = "RsiAlphaModel"
