.. _data-library-alt-data-us-dept-treasury:

=========================
US Department of Treasury
=========================

|

Demonstration Algorithms
========================

.. list-table::
   :header-rows: 1

   * - C#
     - Python
   * - `USTreasuryYieldCurveRateAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/AltData/USTreasuryYieldCurveRateAlgorithm.cs>`_
     - `USTreasuryYieldCurveRateAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/AltData/USTreasuryYieldCurveRateAlgorithm.py>`_

|

Introduction
============

QuantConnect hosts daily treasury yield curve rate data starting from 1998-01-01. The data is sourced directly from the U.S. Department of the Treasury.

Also known as "Constant Maturity Treasury" (CMT) rates, these market yields are calculated from composites of indicative, bid-side market quotations (not actual transactions) obtained by the Federal Reserve Bank of New York at or near 3:30 PM each trading day.

|

Usage
=====


**Requesting Data**

To add US Department of Treasury data to your algorithm use the ``AddData()`` method to request the data. As with all datasets, you should 
save a reference to the Symbol for easy use later in your algorithm. For detailed documentation on using custom data see `Importing Custom Data <https://www.quantconnect.com/03-Algorithm-Reference/04-importing-custom-data.html>`_

.. tabs::
  .. code-tab:: c#
       // U.S. Treasury yield curve rate
       var rates = AddData<USTreasuryYieldCurveRate>("USTYCR").Symbol;
  
  .. code-tab:: py
       # U.S. Treasury yield curve rate
       rates = self.AddData(USTreasuryYieldCurveRate, "USTYCR").Symbol

**Accessing Data**

Data can be accessed via Slice events. Slice delivers unique events to your algorithm as they happen.
We recommend saving the symbol object when you add the data for easy access to slice later.
Data is available in daily resolution.


You can see an example of the slice accessor in the code below.

.. tabs::
   .. code-tab:: c#
        using QuantConnect.Data.Custom.USTreasury;
        namespace QuantConnect.Algorithm.CSharp {
            public class USTreasuryAlgorithm : QCAlgorithm {
                public override void Initialize() {
                    SetStartDate(2019, 1, 1);
                    
                    // U.S. Treasury yield curve rate
                    AddData<USTreasuryYieldCurveRate>("USTYCR");
                }
                
                public override void OnData(Slice data) {
                    // Accessing via Slice event
                    var rates = data.Get<USTreasuryYieldCurveRate>();
                    
                    foreach (var rates in rates.Values)
                    {
                        Log($"One month yield curve: {rates.OneMonth}, Two month yield curve: {rates.TwoMonth}, Three month yield curve: {rates.ThreeMonth}, Six month yield curve: {rates.SixMonth}, One year yield curve: {rates.OneYear}");
                    }
                }
            }
        }

   .. code-tab:: py
        from QuantConnect.Data.Custom.USTreasury import *

        class USTreasuryAlgorithm(QCAlgorithm):
            def Initialize(self):
                self.SetStartDate(2019, 1, 1)

                # U.S. Treasury yield curve rate
                self.AddData(USTreasuryYieldCurveRate, "USTYCR")

            def OnData(self, data):
                # Accessing via Slice event
                rates = data.Get(USTreasuryYieldCurveRate)
                
                for rates in rates.Values:
                    self.Log(f"One month yield curve: {rates.OneMonth}, Two month yield curve: {rates.TwoMonth}, Three month yield curve: {rates.ThreeMonth}, Six month yield curve: {rates.SixMonth}, One year yield curve: {rates.OneYear}")

All custom data has the properties ``Time``, ``Symbol``, and ``Value``.

|

Historical Data
===============

You can request historical custom data in your algorithm using the custom data Symbol object. To learn more about historical 
data requests, please visit 
the `Historical Data <https://www.quantconnect.com/docs/03-Algorithm-Reference/12-historical-data.html>`_
documentation. If there is no custom data in the period you request, the history result will be empty. The following example 
gets u.s. treasury yield curve rate historical data using the History API.

.. tabs::
   .. code-tab:: c#
        var rates = AddData<USTreasuryYieldCurveRate>("USTYCR").Symbol;
        
        // Request 60 days of u.s. treasury yield curve rate history with the rates Symbol
        var ratesHistory = History<USTreasuryYieldCurveRate>(rates, 60, Resolution.Daily);

   .. code-tab:: py
        rates = self.AddData(USTreasuryYieldCurveRate, "USTYCR").Symbol
        
        # Request 60 days of u.s. treasury yield curve rate history with the rates Symbol
        ratesHistory = self.History(USTreasuryYieldCurveRate, rates, 60, Resolution.Daily)

|

Data Properties
===============

**USTreasuryYieldCurveRate**

.. qc-alt-data-properties:: QuantConnect.Data.Custom.USTreasury.USTreasuryYieldCurveRate


|



Demonstration
=============

The following demonstration adds yield curve rate data to the algorithm and trades the ticker SPY depending on whether the 2-year rate is greater than the 10-year rate (`C# Equivalent <https://www.quantconnect.com/terminal/processCache?request=embedded_backtest_915d73387e94f24854c65b887118afbc.html>`_)

.. raw:: html

   <iframe style="border: solid 1px #ebecee; width: 100%; height: 330px" src="https://www.quantconnect.com/terminal/processCache?request=embedded_backtest_b1089dc7df5e147fa2b6eaf0a59bbb27.html"></iframe>

Personal Trading
================

QuantConnect provides this data set for personal use. Nothing special is needed for personal live trading.

|

About the Provider
==================

.. figure:: https://cdn.quantconnect.com/docs/i/us_dept_treasury_logo_rev0.png
   :width: 200
   :align: right

The Treasury Department is the executive agency responsible for promoting economic prosperity and ensuring the financial security of the United States. The Department is responsible for a wide range of activities such as advising the President on economic and financial issues, encouraging sustainable economic growth, and fostering improved governance in financial institutions. The Department of the Treasury operates and maintains systems that are critical to the nation's financial infrastructure, such as the production of coin and currency, the disbursement of payments to the American public, revenue collection, and the borrowing of funds necessary to run the federal government.

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