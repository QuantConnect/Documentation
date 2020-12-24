.. _data-library-alt-data-benzinga-news:

========
Benzinga
========

|

Demonstration Algorithms
========================

.. list-table::
   :header-rows: 1

   * - C#
     - Python
   * - `BenzingaNewsAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/AltData/BenzingaNewsAlgorithm.cs>`_
     - `BenzingaNewsAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/AltData/BenzingaNewsAlgorithm.py>`_

|

Introduction
============

QuantConnect hosts Benzinga News data for U.S. equities. The data contains the article's headline, body, and any tickers mentioned in the body of the article for each news item. On average, there has been approximately 250 new articles published per day since September 12th, 2017.

Each `BenzingaNews` object has 7 key properties you can use in your analysis: Title, Teaser, Contents, Author, Symbols, Categories, and Tags.

|

Usage
=====

Benzinga data is a *linked* data source. This means that the data is automatically tied to the
underlying equity whenever possible. To add the correct data to your algorithm, you should use
the *equity* asset Symbol in ``AddData``.

**Requesting Data**

To add Benzinga data to your algorithm use the ``AddData()`` method to request the data. As with all datasets, you should 
save a reference to the Symbol for easy use later in your algorithm. For detailed documentation on using custom data see `Importing Custom Data <https://www.quantconnect.com/03-Algorithm-Reference/04-importing-custom-data.html>`_

.. tabs::
  .. code-tab:: c#
       // Request underlying equity data.
       var aapl = AddEquity("AAPL", Resolution.Daily).Symbol;
       // AAPL news
       var aaplNews = AddData<BenzingaNews>(aapl).Symbol;
  
  .. code-tab:: py
       # Request underlying equity data.
       aapl = self.AddEquity("AAPL", Resolution.Daily).Symbol
       # AAPL news
       aaplNews = self.AddData(BenzingaNews, aapl).Symbol

**Accessing Data**

Data can be accessed via Slice events. Slice delivers unique events to your algorithm as they happen.
We recommend saving the symbol object when you add the data for easy access to slice later.
Data is available in daily resolution.

Since this is a linked data source, it is also available on the underlying 
security via the ``Securities[symbol].Data.GetAll<BenzingaNews>()`` helper method. 
When you request data via the data cache, it will always return the last article stored.

You can see an example of both of these accessors in the code below.

.. tabs::
   .. code-tab:: c#
        using QuantConnect.Data.Custom.Benzinga;
        namespace QuantConnect.Algorithm.CSharp {
            public class BenzingaAlgorithm : QCAlgorithm {
                public override void Initialize() {
                    SetStartDate(2019, 1, 1);
                    
                    var aapl = AddEquity("AAPL").Symbol;
                    // AAPL news
                    AddData<BenzingaNews>(aapl);
                }
                
                public override void OnData(Slice data) {
                    // Accessing a linked source from securities collection:
                    //var aaplNews = Securities["AAPL"].Data.GetAll<BenzingaNews>();
                    // Accessing via Slice event
                    var aaplNews = data.Get<BenzingaNews>();
                    
                    foreach (var news in aaplNews.Values)
                    {
                        Log($"Unique ID assigned to the article by Benzinga: {news.Id}, Author of the article: {news.Author}, Date the article was published: {news.CreatedAt}, Date that the article was revised on: {news.UpdatedAt}, Title of the article published: {news.Title}");
                    }
                }
            }
        }

   .. code-tab:: py
        from QuantConnect.Data.Custom.Benzinga import *

        class BenzingaAlgorithm(QCAlgorithm):
            def Initialize(self):
                self.SetStartDate(2019, 1, 1)

                aapl = self.AddEquity("AAPL").Symbol

                # AAPL news
                self.AddData(BenzingaNews, aapl)

            def OnData(self, data):
                # Accessing a linked source from securities collection:
                #aaplNews = self.Securities["AAPL"].Data.GetAll(BenzingaNews)
                # Accessing via Slice event
                aaplNews = data.Get(BenzingaNews)
                
                for news in aaplNews.Values:
                    self.Log(f"Unique ID assigned to the article by Benzinga: {news.Id}, Author of the article: {news.Author}, Date the article was published: {news.CreatedAt}, Date that the article was revised on: {news.UpdatedAt}, Title of the article published: {news.Title}")

All custom data has the properties ``Time``, ``Symbol``, and ``Value``.

|

Historical Data
===============

You can request historical custom data in your algorithm using the custom data Symbol object. To learn more about historical 
data requests, please visit 
the `Historical Data <https://www.quantconnect.com/docs/03-Algorithm-Reference/12-historical-data.html>`_
documentation. If there is no custom data in the period you request, the history result will be empty. The following example 
gets aapl news historical data using the History API.

.. tabs::
   .. code-tab:: c#
        // Add underlying equity 
        var aapl = AddEquity("AAPL", Resolution.Daily).Symbol;
        var aaplNews = AddData<BenzingaNews>(aapl).Symbol;
        
        // Request 60 days of aapl news history with the aaplNews Symbol
        var aaplNewsHistory = History<BenzingaNews>(aaplNews, 60, Resolution.Daily);

   .. code-tab:: py
        # Add underlying equity 
        aapl = self.AddEquity("AAPL", Resolution.Daily).Symbol
        aaplNews = self.AddData(BenzingaNews, aapl).Symbol
        
        # Request 60 days of aapl news history with the aaplNews Symbol
        aaplNewsHistory = self.History(BenzingaNews, aaplNews, 60, Resolution.Daily)

|

Data Properties
===============

**BenzingaNews**

.. qc-alt-data-properties:: QuantConnect.Data.Custom.Benzinga.BenzingaNews


|



Demonstration
=============

Raw text is often analyzed with a technique called Natural Language Processing (NLP). There are many forms of natural language analysis that vary in complexity, but the most simple form is assigning a weighting to individual words and measuring the sum as the sentiment of the text. The demonstration below demonstrates this using Benzinga's News Data. (`C# Equivalent <https://www.quantconnect.com/terminal/processCache?request=embedded_backtest_b13fc99b384ec71ad65d5ac8c86dbb00.html>`_)

.. raw:: html

   <iframe style="border: solid 1px #ebecee; width: 100%; height: 330px" src="https://www.quantconnect.com/terminal/processCache?request=embedded_backtest_31cde5f9dd7bd275ba23aa31b01d204f.html"></iframe>

Personal Trading
================

QuantConnect provides this data set for personal use. Nothing special is needed for personal live trading.

|

About the Provider
==================

.. figure:: https://cdn.quantconnect.com/i/tu/benzinga-logo-rev0.png
   :width: 200
   :align: right

Benzinga is a financial news and analysis service providing timely, actionable insights for investors. It is a dynamic and innovative financial media outlet that empowers investors with unique content that is coveted by Wall Street's top traders.

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
     - Coming Soon.