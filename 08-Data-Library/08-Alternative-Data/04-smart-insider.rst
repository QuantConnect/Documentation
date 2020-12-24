.. _data-library-alt-data-smart-insider:

=============
Smart Insider
=============

|

Demonstration Algorithms
========================

.. list-table::
   :header-rows: 1

   * - C#
     - Python
   * - `SmartInsiderTransactionAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/AltData/SmartInsiderTransactionAlgorithm.cs>`_
     - `SmartInsiderTransactionAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/AltData/SmartInsiderTransactionAlgorithm.py>`_

|

Introduction
============

QuantConnect hosts company buyback intentions and transaction data provided by `Smart Insider <https://www.smartinsider.com>`_. A "buy-back" is when a company repurchases its own stock. It reduces the number of shares available to other investors, which in theory should reduce the supply of shares and increase the stock price.

Smart Insider has two data sets available to use in your algorithm. The *Intentions* data set is an announcement that establishes the intention to buy-back shares of the company. 
When the buy-back occurs this triggers a *Transaction* event with details about the execution of the buyback. *Intention* events always come before the *Transaction* event.

You can view more details about these classes in the ``SmartInsiderIntention`` and ``SmartInsiderTransaction`` objects.


|

Usage
=====

Smart Insider data is a *linked* data source. This means that the data is automatically tied to the
underlying equity whenever possible. To add the correct data to your algorithm, you should use
the *equity* asset Symbol in ``AddData``.

**Requesting Data**

To add Smart Insider data to your algorithm use the ``AddData()`` method to request the data. As with all datasets, you should 
save a reference to the Symbol for easy use later in your algorithm. For detailed documentation on using custom data see `Importing Custom Data <https://www.quantconnect.com/03-Algorithm-Reference/04-importing-custom-data.html>`_

.. tabs::
  .. code-tab:: c#
       // Request underlying equity data.
       var aapl = AddEquity("AAPL", Resolution.Daily).Symbol;
       // AAPL buyback transaction
       var buybackTransaction = AddData<SmartInsiderTransaction>(aapl).Symbol;
       // AAPL buyback intention
       var buybackIntention = AddData<SmartInsiderIntention>(aapl).Symbol;
  
  .. code-tab:: py
       # Request underlying equity data.
       aapl = self.AddEquity("AAPL", Resolution.Daily).Symbol
       # AAPL buyback transaction
       buybackTransaction = self.AddData(SmartInsiderTransaction, aapl).Symbol
       # AAPL buyback intention
       buybackIntention = self.AddData(SmartInsiderIntention, aapl).Symbol

**Accessing Data**

Data can be accessed via Slice events. Slice delivers unique events to your algorithm as they happen.
We recommend saving the symbol object when you add the data for easy access to slice later.
Data is available in daily resolution.

Since this is a linked data source, it is also available on the underlying 
security via the ``Securities[symbol].Data.GetAll<SmartInsiderTransaction>()`` helper method. 
When you request data via the data cache, it will always return the last transaction stored.

You can see an example of both of these accessors in the code below.

.. tabs::
   .. code-tab:: c#
        using QuantConnect.Data.Custom.SmartInsider;
        namespace QuantConnect.Algorithm.CSharp {
            public class SmartInsiderAlgorithm : QCAlgorithm {
                public override void Initialize() {
                    SetStartDate(2019, 1, 1);
                    
                    var aapl = AddEquity("AAPL").Symbol;
                    // AAPL buyback transaction
                    AddData<SmartInsiderTransaction>(aapl);
                    // AAPL buyback intention
                    AddData<SmartInsiderIntention>(aapl);
                }
                
                public override void OnData(Slice data) {
                    // Accessing a linked source from securities collection:
                    //var buybackTransaction = Securities["AAPL"].Data.GetAll<SmartInsiderTransaction>();
                    //var buybackIntention = Securities["AAPL"].Data.GetAll<SmartInsiderIntention>();
                    // Accessing via Slice event
                    var buybackTransaction = data.Get<SmartInsiderTransaction>();
                    var buybackIntention = data.Get<SmartInsiderIntention>();
                    
                    foreach (var transaction in buybackTransaction.Values)
                    {
                        Log($"Date traded through the market: {transaction.BuybackDate}, Describes how transaction was executed: {transaction.Execution}, Describes which entity carried out the transaction: {transaction.ExecutionEntity}, Describes what will be done with those shares following repurchase: {transaction.ExecutionHolding}, Currency of transation (ISO Code): {transaction.Currency}");
                    }
                    foreach (var intention in buybackIntention.Values)
                    {
                        Log($"Describes how the transaction was executed: {intention.Execution}, Describes which entity intends to execute the transaction: {intention.ExecutionEntity}, Describes what will be done with those shares following repurchase: {intention.ExecutionHolding}, Number of shares to be or authorised to be traded: {intention.Amount}, Currency of the value of shares to be/Authorised to be traded (ISO Code): {intention.ValueCurrency}");
                    }
                }
            }
        }

   .. code-tab:: py
        from QuantConnect.Data.Custom.SmartInsider import *

        class SmartInsiderAlgorithm(QCAlgorithm):
            def Initialize(self):
                self.SetStartDate(2019, 1, 1)

                aapl = self.AddEquity("AAPL").Symbol

                # AAPL buyback transaction
                self.AddData(SmartInsiderTransaction, aapl)
                # AAPL buyback intention
                self.AddData(SmartInsiderIntention, aapl)

            def OnData(self, data):
                # Accessing a linked source from securities collection:
                #buybackTransaction = self.Securities["AAPL"].Data.GetAll(SmartInsiderTransaction)
                #buybackIntention = self.Securities["AAPL"].Data.GetAll(SmartInsiderIntention)
                # Accessing via Slice event
                buybackTransaction = data.Get(SmartInsiderTransaction)
                buybackIntention = data.Get(SmartInsiderIntention)
                
                for transaction in buybackTransaction.Values:
                    self.Log(f"Date traded through the market: {transaction.BuybackDate}, Describes how transaction was executed: {transaction.Execution}, Describes which entity carried out the transaction: {transaction.ExecutionEntity}, Describes what will be done with those shares following repurchase: {transaction.ExecutionHolding}, Currency of transation (ISO Code): {transaction.Currency}")
                for intention in buybackIntention.Values:
                    self.Log(f"Describes how the transaction was executed: {intention.Execution}, Describes which entity intends to execute the transaction: {intention.ExecutionEntity}, Describes what will be done with those shares following repurchase: {intention.ExecutionHolding}, Number of shares to be or authorised to be traded: {intention.Amount}, Currency of the value of shares to be/Authorised to be traded (ISO Code): {intention.ValueCurrency}")

All custom data has the properties ``Time``, ``Symbol``, and ``Value``.

|

Historical Data
===============

You can request historical custom data in your algorithm using the custom data Symbol object. To learn more about historical 
data requests, please visit 
the `Historical Data <https://www.quantconnect.com/docs/03-Algorithm-Reference/12-historical-data.html>`_
documentation. If there is no custom data in the period you request, the history result will be empty. The following example 
gets aapl buyback transaction historical data using the History API.

.. tabs::
   .. code-tab:: c#
        // Add underlying equity 
        var aapl = AddEquity("AAPL", Resolution.Daily).Symbol;
        var buybackTransaction = AddData<SmartInsiderTransaction>(aapl).Symbol;
        var buybackIntention = AddData<SmartInsiderIntention>(aapl).Symbol;
        
        // Request 60 days of aapl buyback transaction history with the buybackTransaction Symbol
        var buybackTransactionHistory = History<SmartInsiderTransaction>(buybackTransaction, 60, Resolution.Daily);
        // Request 60 days of aapl buyback intention history with the buybackIntention Symbol
        var buybackIntentionHistory = History<SmartInsiderIntention>(buybackIntention, 60, Resolution.Daily);

   .. code-tab:: py
        # Add underlying equity 
        aapl = self.AddEquity("AAPL", Resolution.Daily).Symbol
        buybackTransaction = self.AddData(SmartInsiderTransaction, aapl).Symbol
        buybackIntention = self.AddData(SmartInsiderIntention, aapl).Symbol
        
        # Request 60 days of aapl buyback transaction history with the buybackTransaction Symbol
        buybackTransactionHistory = self.History(SmartInsiderTransaction, buybackTransaction, 60, Resolution.Daily)
        # Request 60 days of aapl buyback intention history with the buybackIntention Symbol
        buybackIntentionHistory = self.History(SmartInsiderIntention, buybackIntention, 60, Resolution.Daily)

|

Data Properties
===============

**SmartInsiderTransaction**

.. qc-alt-data-properties:: QuantConnect.Data.Custom.SmartInsider.SmartInsiderTransaction

**SmartInsiderIntention**

.. qc-alt-data-properties:: QuantConnect.Data.Custom.SmartInsider.SmartInsiderIntention


|



Demonstration
=============

The following demonstration uses QuantConnect Coarse Universe selection to add Smart Insider custom data to a universe of assets. (`C# Equivalent <https://www.quantconnect.com/terminal/processCache?request=embedded_backtest_342d555fd40dd243dc413ba1481f6c6d.html>`_)

.. raw:: html

   <iframe style="border: solid 1px #ebecee; width: 100%; height: 330px" src="https://www.quantconnect.com/terminal/processCache?request=embedded_backtest_04f9dc2c68b724e65db5c8e8c10befb4.html"></iframe>

Personal Trading
================

QuantConnect provides this data set for personal live trading. At the end of the month, your card on file will be charged the associated fee for this data set. Nothing special is needed in your code to work in live trading.

|

About the Provider
==================

.. figure:: https://cdn.quantconnect.com/docs/i/smart-insider-logo_rev0.png
   :width: 200
   :align: right

Founded in 1994, `Smart Insider <https://www.smartinsider.com>`_ has been a provider of insider transactions and intentions data for over 25 years. They specialize in parsing reports and providing insights for insider transactions and intentions for directors and executives in public companies. Covering 60 markets, Smart Insider reports on all listed stocks in countries that require insiders to report their transactions.

Pricing
=======

.. list-table::
   :header-rows: 1

   * - Application Context
     - Subscription Fee
   * - Backtesting
     - Free
   * - Alpha Streams Use, Competitions
     - Free
   * - Personal Paper or Live Trading
     - Buyback Transactions: $80 USD/mo. -- Insider Trading: $120 USD/mo