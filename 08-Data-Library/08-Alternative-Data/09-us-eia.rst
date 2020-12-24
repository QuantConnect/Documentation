.. _data-library-alt-data-us-eia:

===
EIA
===

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

The United States Energy Information Administration (EIA) publishes bulk archives which QuantConnect processes and caches for easy deployment, primarily EIA petroleum data. EIA petroleum data contains roughly 200 metrics such as "Gross Input Into Refineries", "Imports of Distillate Fuel Oil", and "Exports of Total Distillate".

|

Usage
=====


**Requesting Data**

To add EIA data to your algorithm use the ``AddData()`` method to request the data. As with all datasets, you should 
save a reference to the Symbol for easy use later in your algorithm. For detailed documentation on using custom data see `Importing Custom Data <https://www.quantconnect.com/03-Algorithm-Reference/04-importing-custom-data.html>`_

.. tabs::
  .. code-tab:: c#
       // exports of crude oil
       var crudeExport = AddData<USEnergy>(USEnergy.Petroleum.UnitedStates.WeeklyExportsOfCrudeOil).Symbol;
  
  .. code-tab:: py
       # exports of crude oil
       crudeExport = self.AddData(USEnergy, USEnergy.Petroleum.UnitedStates.WeeklyExportsOfCrudeOil).Symbol

**Accessing Data**

Data can be accessed via Slice events. Slice delivers unique events to your algorithm as they happen.
We recommend saving the symbol object when you add the data for easy access to slice later.
Data is available in daily resolution.


You can see an example of the slice accessor in the code below.

.. tabs::
   .. code-tab:: c#
        using QuantConnect.Data.Custom.USEnergy;
        namespace QuantConnect.Algorithm.CSharp {
            public class USEnergyAlgorithm : QCAlgorithm {
                public override void Initialize() {
                    SetStartDate(2019, 1, 1);
                    
                    // exports of crude oil
                    AddData<USEnergy>(USEnergy.Petroleum.UnitedStates.WeeklyExportsOfCrudeOil);
                }
                
                public override void OnData(Slice data) {
                    // Accessing via Slice event
                    var crudeExport = data.Get<USEnergy>();
                    
                    foreach (var crude in crudeExport.Values)
                    {
                        Log($"Value: {crude.Value}");
                    }
                }
            }
        }

   .. code-tab:: py
        from QuantConnect.Data.Custom.USEnergy import *

        class USEnergyAlgorithm(QCAlgorithm):
            def Initialize(self):
                self.SetStartDate(2019, 1, 1)

                # exports of crude oil
                self.AddData(USEnergy, USEnergy.Petroleum.UnitedStates.WeeklyExportsOfCrudeOil)

            def OnData(self, data):
                # Accessing via Slice event
                crudeExport = data.Get(USEnergy)
                
                for crude in crudeExport.Values:
                    self.Log(f"Value: {crude.Value}")

All custom data has the properties ``Time``, ``Symbol``, and ``Value``.

|

Historical Data
===============

You can request historical custom data in your algorithm using the custom data Symbol object. To learn more about historical 
data requests, please visit 
the `Historical Data <https://www.quantconnect.com/docs/03-Algorithm-Reference/12-historical-data.html>`_
documentation. If there is no custom data in the period you request, the history result will be empty. The following example 
gets exports of crude oil historical data using the History API.

.. tabs::
   .. code-tab:: c#
        var crudeExport = AddData<USEnergy>(USEnergy.Petroleum.UnitedStates.WeeklyExportsOfCrudeOil).Symbol;
        
        // Request 60 days of exports of crude oil history with the crudeExport Symbol
        var crudeExportHistory = History<USEnergy>(crudeExport, 60, Resolution.Daily);

   .. code-tab:: py
        crudeExport = self.AddData(USEnergy, USEnergy.Petroleum.UnitedStates.WeeklyExportsOfCrudeOil).Symbol
        
        # Request 60 days of exports of crude oil history with the crudeExport Symbol
        crudeExportHistory = self.History(USEnergy, crudeExport, 60, Resolution.Daily)

|

Data Properties
===============

**USEnergy**

.. qc-alt-data-properties:: QuantConnect.Data.Custom.USEnergy.USEnergy

|

Petroleum Datasets
==================


.. qc-field-value-table:: United States,Ticker,QuantConnect.Data.Custom.USEnergy.USEnergy+Petroleum+UnitedStates

.. qc-field-value-table:: Equatorial Guinea,Ticker,QuantConnect.Data.Custom.USEnergy.USEnergy+Petroleum+EquatorialGuinea

.. qc-field-value-table:: Iraq,Ticker,QuantConnect.Data.Custom.USEnergy.USEnergy+Petroleum+Iraq

.. qc-field-value-table:: Kuwait,Ticker,QuantConnect.Data.Custom.USEnergy.USEnergy+Petroleum+Kuwait

.. qc-field-value-table:: Mexico,Ticker,QuantConnect.Data.Custom.USEnergy.USEnergy+Petroleum+Mexico

.. qc-field-value-table:: Nigeria,Ticker,QuantConnect.Data.Custom.USEnergy.USEnergy+Petroleum+Nigeria

.. qc-field-value-table:: Norway,Ticker,QuantConnect.Data.Custom.USEnergy.USEnergy+Petroleum+Norway

.. qc-field-value-table:: Russia,Ticker,QuantConnect.Data.Custom.USEnergy.USEnergy+Petroleum+Russia

.. qc-field-value-table:: Saudi Arabia,Ticker,QuantConnect.Data.Custom.USEnergy.USEnergy+Petroleum+SaudiArabia

.. qc-field-value-table:: United Kingdom,Ticker,QuantConnect.Data.Custom.USEnergy.USEnergy+Petroleum+UnitedKingdom

.. qc-field-value-table:: Venezuela,Ticker,QuantConnect.Data.Custom.USEnergy.USEnergy+Petroleum+Venezuela

.. qc-field-value-table:: Algeria,Ticker,QuantConnect.Data.Custom.USEnergy.USEnergy+Petroleum+Algeria

.. qc-field-value-table:: Angola,Ticker,QuantConnect.Data.Custom.USEnergy.USEnergy+Petroleum+Angola

.. qc-field-value-table:: Brazil,Ticker,QuantConnect.Data.Custom.USEnergy.USEnergy+Petroleum+Brazil

.. qc-field-value-table:: Canada,Ticker,QuantConnect.Data.Custom.USEnergy.USEnergy+Petroleum+Canada

.. qc-field-value-table:: Congo,Ticker,QuantConnect.Data.Custom.USEnergy.USEnergy+Petroleum+Congo

.. qc-field-value-table:: Colombia,Ticker,QuantConnect.Data.Custom.USEnergy.USEnergy+Petroleum+Colombia

.. qc-field-value-table:: Ecuador,Ticker,QuantConnect.Data.Custom.USEnergy.USEnergy+Petroleum+Ecuador

Demonstration
=============

 (`C# Equivalent <https://www.quantconnect.com/terminal/processCache?request=embedded_backtest_291a87005ea2d574080574c1ea63c4cb.html>`_)

.. raw:: html

   <iframe style="border: solid 1px #ebecee; width: 100%; height: 330px" src="https://www.quantconnect.com/terminal/processCache?request=embedded_backtest_adc601d38b90ab154b41d0f67a0894e7.html"></iframe>

Personal Trading
================

QuantConnect provides this data set for personal use. Nothing special is needed for personal live trading.

|

About the Provider
==================

.. figure:: https://cdn.quantconnect.com/docs/i/eia_logo.png
   :width: 200
   :align: right

The U.S. Energy Information Administration (EIA) collects, analyzes, and disseminates independent and impartial energy information to promote sound policy making, efficient markets, and public understanding of energy and its interaction with the economy and the environment.


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