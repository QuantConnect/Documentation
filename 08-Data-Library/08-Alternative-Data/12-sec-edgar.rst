.. _data-library-alt-data-sec-edgar:

===
SEC
===

|

Demonstration Algorithms
========================

.. list-table::
   :header-rows: 1

   * - C#
     - Python
   * - `SECReport8KAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/AltData/SECReport8KAlgorithm.cs>`_
     - `SECReport8KAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/AltData/SECReport8KAlgorithm.py>`_

|

Introduction
============

QuantConnect hosts SEC filing data for U.S. Equities sourced directly from the SEC website. The data contains properties related to the SEC filing as well as the report contents and attachments. By law, the SEC requires publicly listed corporations to file any important investor notices (8-K), quarterly reports (10-Q), yearly reports (10-K), and various other reports that are made publicly available for investors to access.

The report contents are not parsed in any way. The contents range from plain text to `XBRL <https://en.wikipedia.org/wiki/XBRL>`_ structured data format. For more information about the SEC and structured data formats, please visit the SEC's `Office of Structured Disclosure <https://www.sec.gov/structureddata>`_ webpage.

|

Usage
=====

SEC data is a *linked* data source. This means that the data is automatically tied to the
underlying equity whenever possible. To add the correct data to your algorithm, you should use
the *equity* asset Symbol in ``AddData``.

**Requesting Data**

To add SEC data to your algorithm use the ``AddData()`` method to request the data. As with all datasets, you should 
save a reference to the Symbol for easy use later in your algorithm. For detailed documentation on using custom data see `Importing Custom Data <https://www.quantconnect.com/03-Algorithm-Reference/04-importing-custom-data.html>`_

.. tabs::
  .. code-tab:: c#
       // Request underlying equity data.
       var aapl = AddEquity("AAPL", Resolution.Daily).Symbol;
       // AAPL 8-K report
       var report8k = AddData<SECReport8K>(aapl).Symbol;
       // AAPL 10-K report
       var report10k = AddData<SECReport10K>(aapl).Symbol;
       // AAPL 10-Q report
       var report10q = AddData<SECReport10Q>(aapl).Symbol;
  
  .. code-tab:: py
       # Request underlying equity data.
       aapl = self.AddEquity("AAPL", Resolution.Daily).Symbol
       # AAPL 8-K report
       report8k = self.AddData(SECReport8K, aapl).Symbol
       # AAPL 10-K report
       report10k = self.AddData(SECReport10K, aapl).Symbol
       # AAPL 10-Q report
       report10q = self.AddData(SECReport10Q, aapl).Symbol

**Accessing Data**

Data can be accessed via Slice events. Slice delivers unique events to your algorithm as they happen.
We recommend saving the symbol object when you add the data for easy access to slice later.
Data is available in daily resolution.

Since this is a linked data source, it is also available on the underlying 
security via the ``Securities[symbol].Data.GetAll<SECReport8K>()`` helper method. 
When you request data via the data cache, it will always return the last 8-K report stored.

You can see an example of both of these accessors in the code below.

.. tabs::
   .. code-tab:: c#
        using QuantConnect.Data.Custom.SEC;
        namespace QuantConnect.Algorithm.CSharp {
            public class SECAlgorithm : QCAlgorithm {
                public override void Initialize() {
                    SetStartDate(2019, 1, 1);
                    
                    var aapl = AddEquity("AAPL").Symbol;
                    // AAPL 8-K report
                    AddData<SECReport8K>(aapl);
                    // AAPL 10-K report
                    AddData<SECReport10K>(aapl);
                    // AAPL 10-Q report
                    AddData<SECReport10Q>(aapl);
                }
                
                public override void OnData(Slice data) {
                    // Accessing a linked source from securities collection:
                    //var report8k = Securities["AAPL"].Data.GetAll<SECReport8K>();
                    //var report10k = Securities["AAPL"].Data.GetAll<SECReport10K>();
                    //var report10q = Securities["AAPL"].Data.GetAll<SECReport10Q>();
                    // Accessing via Slice event
                    var report8k = data.Get<SECReport8K>();
                    var report10k = data.Get<SECReport10K>();
                    var report10q = data.Get<SECReport10Q>();
                    
                    foreach (var filing in report8k.Values)
                    {
                        Log($"Contents of the actual SEC report: {filing.Report}");
                    }
                    foreach (var filing in report10k.Values)
                    {
                        Log($"Contents of the actual SEC report: {filing.Report}");
                    }
                    foreach (var filing in report10q.Values)
                    {
                        Log($"Contents of the actual SEC report: {filing.Report}");
                    }
                }
            }
        }

   .. code-tab:: py
        from QuantConnect.Data.Custom.SEC import *

        class SECAlgorithm(QCAlgorithm):
            def Initialize(self):
                self.SetStartDate(2019, 1, 1)

                aapl = self.AddEquity("AAPL").Symbol

                # AAPL 8-K report
                self.AddData(SECReport8K, aapl)
                # AAPL 10-K report
                self.AddData(SECReport10K, aapl)
                # AAPL 10-Q report
                self.AddData(SECReport10Q, aapl)

            def OnData(self, data):
                # Accessing a linked source from securities collection:
                #report8k = self.Securities["AAPL"].Data.GetAll(SECReport8K)
                #report10k = self.Securities["AAPL"].Data.GetAll(SECReport10K)
                #report10q = self.Securities["AAPL"].Data.GetAll(SECReport10Q)
                # Accessing via Slice event
                report8k = data.Get(SECReport8K)
                report10k = data.Get(SECReport10K)
                report10q = data.Get(SECReport10Q)
                
                for filing in report8k.Values:
                    self.Log(f"Contents of the actual SEC report: {filing.Report}")
                for filing in report10k.Values:
                    self.Log(f"Contents of the actual SEC report: {filing.Report}")
                for filing in report10q.Values:
                    self.Log(f"Contents of the actual SEC report: {filing.Report}")

All custom data has the properties ``Time``, ``Symbol``, and ``Value``.

|

Historical Data
===============

You can request historical custom data in your algorithm using the custom data Symbol object. To learn more about historical 
data requests, please visit 
the `Historical Data <https://www.quantconnect.com/docs/03-Algorithm-Reference/12-historical-data.html>`_
documentation. If there is no custom data in the period you request, the history result will be empty. The following example 
gets aapl 8-k report historical data using the History API.

.. tabs::
   .. code-tab:: c#
        // Add underlying equity 
        var aapl = AddEquity("AAPL", Resolution.Daily).Symbol;
        var report8k = AddData<SECReport8K>(aapl).Symbol;
        var report10k = AddData<SECReport10K>(aapl).Symbol;
        var report10q = AddData<SECReport10Q>(aapl).Symbol;
        
        // Request 60 days of aapl 8-k report history with the report8k Symbol
        var report8kHistory = History<SECReport8K>(report8k, 60, Resolution.Daily);
        // Request 60 days of aapl 10-k report history with the report10k Symbol
        var report10kHistory = History<SECReport10K>(report10k, 60, Resolution.Daily);
        // Request 60 days of aapl 10-q report history with the report10q Symbol
        var report10qHistory = History<SECReport10Q>(report10q, 60, Resolution.Daily);

   .. code-tab:: py
        # Add underlying equity 
        aapl = self.AddEquity("AAPL", Resolution.Daily).Symbol
        report8k = self.AddData(SECReport8K, aapl).Symbol
        report10k = self.AddData(SECReport10K, aapl).Symbol
        report10q = self.AddData(SECReport10Q, aapl).Symbol
        
        # Request 60 days of aapl 8-k report history with the report8k Symbol
        report8kHistory = self.History(SECReport8K, report8k, 60, Resolution.Daily)
        # Request 60 days of aapl 10-k report history with the report10k Symbol
        report10kHistory = self.History(SECReport10K, report10k, 60, Resolution.Daily)
        # Request 60 days of aapl 10-q report history with the report10q Symbol
        report10qHistory = self.History(SECReport10Q, report10q, 60, Resolution.Daily)

|

Data Properties
===============

**SECReport8K**

.. qc-alt-data-properties:: QuantConnect.Data.Custom.SEC.SECReport8K

**SECReport10K**

.. qc-alt-data-properties:: QuantConnect.Data.Custom.SEC.SECReport10K

**SECReport10Q**

.. qc-alt-data-properties:: QuantConnect.Data.Custom.SEC.SECReport10Q


|



Demonstration
=============

The following demonstration uses QuantConnect Coarse Universe selection to add SEC custom data to a universe of assets. (`C# Equivalent <https://www.quantconnect.com/terminal/processCache?request=embedded_backtest_d138860b2794e97458cdfa95fda36b3b.html>`_)

.. raw:: html

   <iframe style="border: solid 1px #ebecee; width: 100%; height: 330px" src="https://www.quantconnect.com/terminal/processCache?request=embedded_backtest_7dacd38ad61d5f84dd7f631eaa726e8f.html"></iframe>

Personal Trading
================

QuantConnect provides this data set for personal use. Nothing special is needed for personal live trading.

|

About the Provider
==================

.. figure:: https://cdn.quantconnect.com/docs/i/sec-logo.png
   :width: 200
   :align: right

The mission of the U.S. Securities and Exchange Commission is to protect investors, maintain fair, orderly, and efficient markets, and facilitate capital formation. The SEC oversees the key participants in the securities world, including securities exchanges, securities brokers and dealers, investment advisors, and mutual funds. The SEC is concerned primarily with promoting the disclosure of important market-related information, maintaining fair dealing, and protecting against fraud.

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