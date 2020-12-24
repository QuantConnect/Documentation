.. _data-library-alt-data-us-fred:

====
FRED
====

|

Demonstration Algorithms
========================

.. list-table::
   :header-rows: 1

   * - C#
     - Python
   * - `CachedAlternativeDataAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/AltData/CachedAlternativeDataAlgorithm.cs>`_
     - `CachedAlternativeDataAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/AltData/CachedAlternativeDataAlgorithm.py>`_

|

Introduction
============

QuantConnect hosts Federal Reserve Economics Data (FRED) for use in your algorithms. The Economic Research division from the Federal Reserve bank of St. Louis, MO provides free macro-economics indicators and other figures.

|

Usage
=====


**Requesting Data**

To add FRED data to your algorithm use the ``AddData()`` method to request the data. As with all datasets, you should 
save a reference to the Symbol for easy use later in your algorithm. For detailed documentation on using custom data see `Importing Custom Data <https://www.quantconnect.com/03-Algorithm-Reference/04-importing-custom-data.html>`_

.. tabs::
  .. code-tab:: c#
       // intervention in market transactions
       var interventions = AddData<Fred>(Fred.CentralBankInterventions.USInterventionInMarketTransactionsInTheJpyUsd).Symbol;
  
  .. code-tab:: py
       # intervention in market transactions
       interventions = self.AddData(Fred, Fred.CentralBankInterventions.USInterventionInMarketTransactionsInTheJpyUsd).Symbol

**Accessing Data**

Data can be accessed via Slice events. Slice delivers unique events to your algorithm as they happen.
We recommend saving the symbol object when you add the data for easy access to slice later.
Data is available in daily resolution.


You can see an example of the slice accessor in the code below.

.. tabs::
   .. code-tab:: c#
        using QuantConnect.Data.Custom.Fred;
        namespace QuantConnect.Algorithm.CSharp {
            public class FredAlgorithm : QCAlgorithm {
                public override void Initialize() {
                    SetStartDate(2019, 1, 1);
                    
                    // intervention in market transactions
                    AddData<Fred>(Fred.CentralBankInterventions.USInterventionInMarketTransactionsInTheJpyUsd);
                }
                
                public override void OnData(Slice data) {
                    // Accessing via Slice event
                    var interventions = data.Get<Fred>();
                    
                    foreach (var intervention in interventions.Values)
                    {
                        Log($"Value: {intervention.Value}");
                    }
                }
            }
        }

   .. code-tab:: py
        from QuantConnect.Data.Custom.Fred import *

        class FredAlgorithm(QCAlgorithm):
            def Initialize(self):
                self.SetStartDate(2019, 1, 1)

                # intervention in market transactions
                self.AddData(Fred, Fred.CentralBankInterventions.USInterventionInMarketTransactionsInTheJpyUsd)

            def OnData(self, data):
                # Accessing via Slice event
                interventions = data.Get(Fred)
                
                for intervention in interventions.Values:
                    self.Log(f"Value: {intervention.Value}")

All custom data has the properties ``Time``, ``Symbol``, and ``Value``.

|

Historical Data
===============

You can request historical custom data in your algorithm using the custom data Symbol object. To learn more about historical 
data requests, please visit 
the `Historical Data <https://www.quantconnect.com/docs/03-Algorithm-Reference/12-historical-data.html>`_
documentation. If there is no custom data in the period you request, the history result will be empty. The following example 
gets intervention in market transactions historical data using the History API.

.. tabs::
   .. code-tab:: c#
        var interventions = AddData<Fred>(Fred.CentralBankInterventions.USInterventionInMarketTransactionsInTheJpyUsd).Symbol;
        
        // Request 60 days of intervention in market transactions history with the interventions Symbol
        var interventionsHistory = History<Fred>(interventions, 60, Resolution.Daily);

   .. code-tab:: py
        interventions = self.AddData(Fred, Fred.CentralBankInterventions.USInterventionInMarketTransactionsInTheJpyUsd).Symbol
        
        # Request 60 days of intervention in market transactions history with the interventions Symbol
        interventionsHistory = self.History(Fred, interventions, 60, Resolution.Daily)

|

Data Properties
===============

**Fred**

.. qc-alt-data-properties:: QuantConnect.Data.Custom.Fred.Fred


|



Demonstration
=============

 (`C# Equivalent <https://www.quantconnect.com/terminal/processCache?request=embedded_backtest_5024157df2649994cbf576b1e8d8951a.html>`_)

.. raw:: html

   <iframe style="border: solid 1px #ebecee; width: 100%; height: 330px" src="https://www.quantconnect.com/terminal/processCache?request=embedded_backtest_8f7d2c193924b61dcee9a949e12c833c.html"></iframe>

Personal Trading
================

QuantConnect provides this data set for personal use. Nothing special is needed for personal live trading.

|

About the Provider
==================

.. figure:: https://cdn.quantconnect.com/docs/i/fred_logo.png
   :width: 200
   :align: right

The widely used FRED data service is updated daily and allows 24/7 access to over 500,000 financial and economic data series from more than 85 public and proprietary sources.

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
     - Free 