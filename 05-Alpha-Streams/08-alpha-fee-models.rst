.. _alpha-streams-alpha-fee-models:

================================
Alpha Streams - Alpha Fee Models
================================

|

Model Implementations
=====================

.. list-table::
   :header-rows: 0

   * - `Alpha Streams Brokerage Model <https://github.com/QuantConnect/Lean/blob/master/Common/Brokerages/AlphaStreamsBrokerageModel.cs>`_
   * - `Alpha Streams Fee Model <https://github.com/QuantConnect/Lean/blob/master/Common/Orders/Fees/AlphaStreamsFeeModel.cs>`_
   * - `Alpha Streams Slippage Model <https://github.com/QuantConnect/Lean/blob/master/Common/Orders/Slippage/AlphaStreamsSlippageModel.cs>`_.

|

Introduction
============
Models submitted to Alpha Streams need to use realistic cost and execution models. To standardize this for the Alpha Market, QuantConnect has created models based on industry feedback, which model the costs of execution through a `prime brokerage <https://en.wikipedia.org/wiki/Prime_brokerage>`_.

|

Required Settings
=================
Using a single line of code, you can set all of the reality models required for an Alpha Stream. This is done via a Brokerage Model. The brokerage model sets all your asset fees, slippage, and margin models to the required settings automatically. This should be called in your ``Initialize()`` method.

.. tabs::

   .. code-tab:: c#

        //Set fee, slippage and margin models automatically.
        SetBrokerageModel(BrokerageName.AlphaStreams);

   .. code-tab:: py

        # Set fee, slippage and margin models automatically.
        self.SetBrokerageModel(BrokerageName.AlphaStreams)

Behind the scenes, this sets your models to the ``AlphaStreamsBrokerageModel``, ``AlphaStreamsFeeModel``, and ``AlphaStreamsSlippageModel``. In the following sections, we'll explore these models in more detail.

|

Alpha Transaction Fees
======================
The Alpha Stream `fee model implementation <https://github.com/QuantConnect/Lean/blob/master/Common/Orders/Fees/AlphaStreamsFeeModel.cs>`_ provides the following cost structure for trading in Alpha Models:

.. list-table::
   :header-rows: 1

   * - Asset Class
     - Fees

   * - US Equity
     - 0.005 per share (minimum of $1.00)

   * - US Futures
     - $0.50 per contract

   * - US Options
     - $0.50 per contract

   * - Forex
     - 0.02 basis points per transaction

|

Alpha Slippage Models
=====================
Alpha Streams assumes a 1 basis point (0.01%) slippage on US Equity trades. All other asset classes are assumed to be filled at the asking price. The full implementation of the slippage model is available on `Github <https://github.com/quantconnect/Lean/blob/master/Common/Orders/Slippage/AlphaStreamsSlippageModel.cs>`_.

|

Alpha Borrowing Costs
=====================
Currently, LEAN does not support modeling borrowing costs, so shorts are free. In the coming months, the QuantConnect team will build this feature, and all shorting activity will incur interest costs.

|

Alpha Leverage
==============
When a proprietary trading firm deposits margin with a prime brokerage they are offered 10-20x leverage to perform trading as long as they stay within target risk parameters. For this reason, control over "leverage" is always done by the institution, and strategies are forced to use 1x leverage. If you require more buying power, increase your starting portfolio allocation cash.

