.. _algorithm-framework-portfolio-construction:

============================================
Algorithm Framework - Portfolio Construction
============================================

|

.. list-table:: Demonstration Algorithms
   :header-rows: 1

   * - C#
     - Py
   * - `NullPortfolioConstructionModel.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm/Portfolio/NullPortfolioConstructionModel.cs>`_
     - `NullPortfolioConstructionModel.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm/Portfolio/NullPortfolioConstructionModel.py>`_
   * - `EqualWeightingPortfolioConstructionModel.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/EqualWeightingPortfolioConstructionModel.cs>`_
     - `EqualWeightingPortfolioConstructionModel.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/EqualWeightingPortfolioConstructionModel.py>`_
   * - `MeanVarianceOptimizationPortfolioConstructionModel.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/MeanVarianceOptimizationPortfolioConstructionModel.cs>`_
     - `MeanVarianceOptimizationPortfolioConstructionModel.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/MeanVarianceOptimizationPortfolioConstructionModel.py>`_
   * - `BlackLittermanPortfolioConstructionModel.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/BlackLittermanOptimizationPortfolioConstructionModel.cs>`_
     - `BlackLittermanPortfolioConstructionModel=.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/BlackLittermanOptimizationPortfolioConstructionModel.py>`_

|

Introduction
============

.. figure:: https://cdn.quantconnect.com/web/i/docs/algorithm-framework/portfolio-construction.png

The Portfolio Construction Model receives ``Insight`` objects from the `Alpha Model <https://www.quantconnect.com/docs/algorithm-framework/alpha-creation>`_ and uses them to create ``PortfolioTarget`` objects for the execution model. A Portfolio Target provides the number of units of the asset we'd like to hold. To set your portfolio construction model you should use the ``self.SetPortfolioConstruction( IPortfolioConstructionModel )`` method. This should be done from your algorithm ``def Initialize()`` method.

|

Portfolio Construction Model Structure
======================================

Portfolio Construction Models have one primary method: ``CreateTargets()``. This method takes the algorithm object and a list of ``Insight`` objects. You should seek to answer the question "how many shares/contracts should I buy based on the insight predictions I've been presented?"

Like all Algorithm Framework models, the Portfolio Construction Model also receives the ``OnSecuritiesChanged()`` event, which can optionally be used to perform actions when there are security additions or removals. If you are inheriting from the ``PortfolioConstructionModel`` base class, this is an optional method.

.. tabs::

   .. code-tab:: c#

        // Portfolio construction scaffolding class; basic method args.
        class MyPortfolioConstructionModel : PortfolioConstructionModel
        {
            // Create list of PortfolioTarget objects from Insights
            List<IPortfolioTarget> CreateTargets(QCAlgorithmFramework algorithm, Insight[] insights)
            {

            }

            // OPTIONAL: Security change details
            void OnSecuritiesChanged(QCAlgorithmFramework algorithm, SecurityChanges changes)
            {
                // Security additions and removals are pushed here.
                // This can be used for setting up algorithm state
           }
        }
   .. code-tab:: py

        # Portfolio construction scaffolding class; basic method args.
        class MyPortfolioConstructionModel(PortfolioConstructionModel):

        # Create list of PortfolioTarget objects from Insights
        def CreateTargets(self, algorithm, insights):
            pass

        # OPTIONAL: Security change details
        def OnSecuritiesChanged(self, algorithm, changes):
            # Security additions and removals are pushed here.
            # This can be used for setting up algorithm state.
            # changes.AddedSecurities:
            # changes.RemovedSecurities:
            pass

|

Creating Portfolio Targets
==========================

The PortfolioTarget class accepts two parameters for its constructor: Symbol and Quantity. This is consumed by the execution model, which seeks to reach this target as efficiently as possible; you should not assume orders are filled immediately.

.. tabs::

   .. code-tab:: c#

        // Create a new portfolio target for 1200 IBM shares.
        var target = new PortfolioTarget("IBM", 1200);

   .. code-tab:: py

        # Create a new portfolio target for 1200 IBM shares.
        target = PortfolioTarget("IBM", 1200)

Margin accounts can also use the ``Percent(algorithm, Symbol, percent)`` helper method. This calculates a quantity equivalent to a percentage of portfolio value.

.. tabs::

   .. code-tab:: c#

        // Calculate target equivalent to 10% of portfolio value
        var target = PortfolioTarget.Percent(algorithm, "IBM", 0.1);

   .. code-tab:: py

        # Calculate target equivalent to 10% of portfolio value
        target = PortfolioTarget.Percent(algorithm, "IBM", 0.1)

Your Portfolio Construction Model should return a targets array from your ``CreateTargets`` method:

.. tabs::

   .. code-tab:: c#

        // Return an array of targets
        return new PortfolioTarget[] {  new PortfolioTarget("IBM", 1200)  };

   .. code-tab:: py

        # Return an array of targets
        return [ PortfolioTarget("IBM", 1200) ]

|

Null Portfolio Construction
===========================

The ``NullPortfolioConstructionModel`` can be used to skip the execution phase of the algorithm, i.e. *do nothing*. This is useful when you're trying to analyze the Alpha Model in isolation. All `Alpha Streams <https://www.quantconnect.com/alpha>`_ algorithms can use Null Portfolio Construction and Null Execution Models.

.. tabs::

   .. code-tab:: c#

        SetPortfolioConstruction( new NullPortfolioConstructionModel() );

   .. code-tab:: py

        self.SetPortfolioConstruction( NullPortfolioConstructionModel() )

You can view the C# *implementation* of this model in `GitHub <https://github.com/QuantConnect/Lean/blob/master/Algorithm/Portfolio/NullPortfolioConstructionModel.cs>`_ or the Python *implementation* of this model in `GitHub <https://github.com/QuantConnect/Lean/blob/master/Algorithm/Portfolio/NullPortfolioConstructionModel.py>`_.

|

Equal Weighting Portfolio Construction
======================================

The Equal Weighting Portfolio Construction Model assigns an equal share of the portfolio to insights supplied to it. This is useful for universe rotation based on simple portfolio strategies. To use it in your algorithm, you need to create an instance of ``EqualWeightingPortfolioConstructionModel``.

.. tabs::

   .. code-tab:: c#

        SetPortfolioConstruction( new EqualWeightingPortfolioConstructionModel() );

   .. code-tab:: py

        self.SetPortfolioConstruction( EqualWeightingPortfolioConstructionModel() )

You can view the C# *implementation* of this model in `GitHub <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/EqualWeightingPortfolioConstructionModel.cs>`_ or the Python *implementation* of this model in `GitHub <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/EqualWeightingPortfolioConstructionModel.py>`_.

|

Mean Variance Portfolio Construction
====================================

The Mean Variance Portfolio Construction Model is an implementation of the classical model. It seeks to build a portfolio with the minimum volatility possible.

You can view the C# *implementation* of this model in `GitHub <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/MeanVarianceOptimizationPortfolioConstructionModel.cs>`_ or the Python *implementation* of this model in `GitHub <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/MeanVarianceOptimizationPortfolioConstructionModel.py>`_.

|

Black Litterman Portfolio Construction
======================================

The Black Litterman Portfolio Construction Model takes Insights from multiple alphas and combines them into a single portfolio. These multiple Alpha Model sources can be seen as the "investor views" required of the classical model.

You can view the C# *implementation* of this model in `GitHub <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/BlackLittermanOptimizationPortfolioConstructionModel.cs>`_ or the Python *implementation* of this model in `GitHub <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Portfolio/BlackLittermanOptimizationPortfolioConstructionModel.py>`_.