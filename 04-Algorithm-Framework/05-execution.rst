.. _algorithm-framework-execution:

=========
Execution
=========

|

.. list-table:: Demonstration Algorithms
   :header-rows: 1

   * - C#
     - Python
   * - `Do Nothing - NullExecutionModel.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm/Execution/NullExecutionModel.cs>`_
     - `Do Nothing - NullExecutionModel.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm/Execution/NullExecutionModel.py>`_
   * - `Immediate Execution - ImmediateExecutionModel.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm/Execution/NullExecutionModel.cs>`_
     - `Immediate Execution - ImmediateExecutionModel.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm/Execution/ImmediateExecutionModel.py>`_
   * - `VWAP Seeking Execution - VolumeWeightedAveragePriceExecutionModel.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Execution/VolumeWeightedAveragePriceExecutionModel.cs>`_
     - `VWAP Seeking Execution - VolumeWeightedAveragePriceExecutionModel.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Execution/VolumeWeightedAveragePriceExecutionModel.py>`_
   * - `Standard Deviation Execution - StandardDeviationExecutionModel.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Execution/StandardDeviationExecutionModel.cs>`_
     - `Standard Deviation Execution - StandardDeviationExecutionModel.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Execution/StandardDeviationExecutionModel.py>`_

|

Introduction
============

.. figure:: https://cdn.quantconnect.com/web/i/docs/algorithm-framework/execute.png

The Execution Model is primarily concerned with efficiently executing trades. It seeks to find the optimal price to fill orders and manages the orders. It receives a ``PortfolioTarget`` array from the :ref:`Portfolio Construction Model <algorithm-framework-portfolio-construction>` and uses it to place trades in the market, seeking to reach the units indicated in the Portfolio Target. To set your execution model, you should use the ``self.SetExecution( IExecutionModel )`` method. This should be done from your algorithm ``def Initialize()`` method.

When the targets arrive at the Execution Model, they have already been risk-adjusted by the Risk Management Model. Like all models, the Execution Model only receives *updates* to the portfolio target share counts. You will not receive all the targets at once.

.. tabs::

   .. code-tab:: c#

        // Set your execution model in the Initialize() method
        SetExecution( new ImmediateExecutionModel() );

   .. code-tab:: py

        # Set your execution model in the def Initialize() method
        self.SetExecution( ImmediateExecutionModel() )

|

Execution Model Structure
=========================

Execution models have one required method to implement ``Execute(algorithm, targets)``. This is responsible for reaching the target portfolios as efficiently as possible. The ``PortfolioTarget`` objects are created by the :ref:`Portfolio Construction Model <algorithm-framework-portfolio-construction>` and then adjusted by the :ref:`Risk Management Module <algorithm-framework-risk-management>`. The final risk-adjusted portfolio targets are delivered to your execution model for fulfillment.

.. tabs::

   .. code-tab:: c#

        // Basic Execution Model Scaffolding Structure Example
        class MyExecutionModel : ExecutionModel {

            // Fill the supplied portfolio targets efficiently.
            public override void Execute(QCAlgorithmFramework algorithm, IPortfolioTarget[] targets)
            {
                // NOP
            }

            //  Optional: Securities changes event for handling new securities.
            public override void OnSecuritiesChanged(QCAlgorithmFramework algorithm, SecurityChanges changes)
            {
            }
        }

   .. code-tab:: py

        from clr import AddReference
        AddReference("QuantConnect.Algorithm.Framework")
        from QuantConnect.Algorithm.Framework.Execution import ExecutionModel

        # Execution Model scaffolding structure example
        class MyExecutionModel(ExecutionModel):

            # Fill the supplied portfolio targets efficiently
                def Execute(self, algorithm, targets):
                    pass

            # Optional: Securities changes event for handling new securities.
            def OnSecuritiesChanged(self, algorithm, changes):
                    pass

The ``PortfolioTarget`` class has the following properties available for use by the Execution Model. They can be accessed with their public properties ``target.Quantity``.

.. tabs::

   .. code-tab:: c#

        // Final target quantity for execution
        class PortfolioTarget : IPortfolioTarget {

            // Asset to be traded.
            Symbol Symbol;

            // Number of units to hold.
            decimal Quantity;
        }

   .. code-tab:: py

        # Final target quantity for execution
        class PortfolioTarget:
            self.Symbol    # Asset to be traded (Symbol object)
            self.Quantity  # Number of units to hold (Decimal)

|

Immediate Execution Model
=========================

The Immediate Execution Model uses market orders to immediately fill algorithm portfolio targets. It is the simplest Execution Model similar to simply placing Market Orders inline with your algorithm logic.

You can use this pre-made Execution Model by setting it in the Initialize method:

.. tabs::

   .. code-tab:: c#

        SetExecution( new ImmediateExecutionModel() );

   .. code-tab:: py

        self.SetExecution( ImmediateExecutionModel() )

It is implemented as demonstrated in the code snippet below:

.. tabs::

   .. code-tab:: c#

        // Issue market orders for the difference between holdings & targeted quantity
        public override void Execute(QCAlgorithmFramework algorithm, IPortfolioTarget[] targets)
        {
            foreach (var target in targets)
            {
                var existing = algorithm.Securities[target.Symbol].Holdings.Quantity + algorithm.Transactions.GetOpenOrders(target.Symbol).Sum(o => o.Quantity);
                var quantity = target.Quantity - existing;
                if (quantity != 0)
                {
                    algorithm.MarketOrder(target.Symbol, quantity);
                }
            }
        }

   .. code-tab:: py

        # Issue market orders for the difference between holdings & targeted quantity
        def Execute(self, algorithm, targets):
            for target in targets:
                open_quantity = sum([x.Quantity for x in algorithm.Transactions.GetOpenOrders(target.Symbol)])
                existing = algorithm.Securities[target.Symbol].Holdings.Quantity + open_quantity
                quantity = target.Quantity - existing
                if quantity != 0:
                    algorithm.MarketOrder(target.Symbol, quantity)

You can view the complete C# *implementation* of this model in `GitHub <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Execution/ImmediateExecutionModel.cs>`_ or the complete Python *implementation* of this model in `GitHub <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Execution/ImmediateExecutionModel.py>`_.

|

VWAP Execution Model
====================

The VWAP Execution Model seeks for the average fill price of your position to match or be better than the volume weighted average price for the trading day. This is a *best-effort* algorithm, and no guarantee can be made that it will reach the VWAP.

.. figure:: https://cdn.quantconnect.com/web/i/docs/algorithm-framework/execution-model-vwap-fill.png

   VWAP Execution Model Fill Placements

To use the pre-made Execution Model in your algorithm, you should set it in Initialize():

.. tabs::

   .. code-tab:: c#

        SetExecution( new VolumeWeightedAveragePriceExecutionModel() );

   .. code-tab:: py

        self.SetExecution( VolumeWeightedAveragePriceExecutionModel() )

You can view the complete C# *implementation* of this model in `GitHub <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Execution/VolumeWeightedAveragePriceExecutionModel.cs>`_ or the complete Python *implementation* of this model in `GitHub <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Execution/VolumeWeightedAveragePriceExecutionModel.py>`_.

|

Standard Deviation Execution Model
==================================

The Standard Deviation Execution Model seeks to fill orders when the price is more than 2 standard deviations lower than the normal stock price for a given period. The intent is to find dips in the market to place trades. Unfortunately, in strongly trending markets, this can result in delayed trade placement as it might be a while before the next price dip.

To use the pre-made Execution Model in your algorithm, you should set it in Initialize():

.. tabs::

   .. code-tab:: c#

        SetExecution( new StandardDeviationExecutionModel() );

   .. code-tab:: py

        self.SetExecution( StandardDeviationExecutionModel() )

This model has the following optional parameters:

.. code-block::

    StandardDeviationExecutionModel( deviations = 2, period = 60, resolution=Resolution.Minute )

* ``deviations`` - Minimum deviations from mean before trading.
* ``period`` - Period of the standard deviation indicator.
* ``resolution`` - Resolution of the deviation indicators.

You can view the complete C# *implementation* of this model in `GitHub <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Execution/StandardDeviationExecutionModel.cs>`_ or the complete Python *implementation* of this model in `GitHub <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Framework/Execution/StandardDeviationExecutionModel.py>`_.