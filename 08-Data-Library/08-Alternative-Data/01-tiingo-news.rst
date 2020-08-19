===========
Tiingo News
===========

|

Demonstration Algorithms
========================

.. list-table::
   :header-rows: 1

   * - C#
     - Python
   * - `TiingoNewsAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/AltData/TiingoNewsAlgorithm.cs>`_
     - `TiingoNewsAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/AltData/TiingoNewsAlgorithm.py>`_

|

Introduction
============

QuantConnect hosts `Tiingo's News API <https://api.tiingo.com/products/news-api>`_ data for US Equities. The data provides the article title, tickers mentioned and a long form description of the news story for each news item. There are approximately 8,000-12,000 articles per day recorded since January, 1st 2014.

Each ``TiingoNews`` object has five key properties you can use in your analysis: Title, Description, Tags, Source, and Symbols.

.. tabs::

   .. code-tab:: c#

        class TiingoNews {
              string Title;                       // Title of the article
              string Description;                 // Sentence summary of article
              List<string> Tags;                  // List of tags for article
              string Source;                      // Name of publication
              List<string> Symbols;               // List of mentioned companies.
        }

   .. code-tab:: py

        class TiingoNews:
              self.Title;                       # Title of the article
              self.Description;                 # Sentence summary of article
              self.Tags;                        # List of tags for article
              self.Source;                      # Name of publication
              self.Symbols;                     # Symbol list of mentioned companies

|

Usage
=====

Tiingo News is a *linked* data source. This means news data is automatically tied to the underlying equity whenever possible. This linkage is performed by the ticker mentioned in the news article at the time of the publication. To add the right news articles to your strategy you should use the *equity* asset symbol.

**Requesting Data**

.. tabs::

   .. code-tab:: c#

        // Request underlying equity data.
        var ibm = AddEquity("IBM", Resolution.Minute).Symbol;
        // Add news data for the underlying IBM asset
        AddData<TiingoNews>(ibm);

   .. code-tab:: py

        # Request underlying equity data.
        ibm = self.AddEquity("IBM", Resolution.Minute).Symbol
        # Add news data for the underlying IBM asset
        self.AddData(TiingoNews, ibm)

**Accessing Data**

Data can be accessed the normal way via the Slice events. Slice events deliver the unique articles to your algorithm as they happen.

Alternatively, since this is a linked data source, it is also available on the underlying secuity via the ``Securities[symbol].Data.GetAll(TiingoNews)`` helper method. When you request data via the data cache, it will always return the *last* news article stored.

You can see an example of both of these accessors in the code below.

.. tabs::

   .. code-tab:: c#

        using QuantConnect.Data.Custom.Tiingo;
        namespace QuantConnect.Algorithm.CSharp
        {
            public class TiingoDemonstration : QCAlgorithm
            {
                public override void Initialize()
                {
                    SetStartDate(2019, 8, 1);
                    SetCash(100000);

                    // Request linked news data for Apple.
                    var aapl = AddEquity("AAPL", Resolution.Minute).Symbol;
                    AddData<TiingoNews>(aapl);
                }

                public override void OnData(Slice data)
                {
                    // Accessing a linked source from securities collection:
                    //var tiingoNews = Securities["AAPL"].Data.GetAll<TiingoNews>();

                    //Accessing via slice event:
                    var tiingoNews = data.Get<TiingoNews>();
                    foreach (var news in tiingoNews.Values)
                    {
                        Log($"Now: {Time} Crawled: {news.CrawlDate} Title: {news.Title}");
                    }
                }
            }
        }

   .. code-tab:: py

        class TiingoNewsDemonstration(QCAlgorithm):

            def Initialize(self):
                self.SetStartDate(2019, 8, 1)
                self.SetCash(100000)

                # Request linked news data for Apple
                s = self.AddEquity("AAPL", Resolution.Minute).Symbol
                self.AddData(TiingoNews, s)

            def OnData(self, data):
                # Accessing most recent news via a linked source from securities collection:
                # (returns a list, you can drop the "Values" from the for loop below)
                #tiingoNews = self.Securities["AAPL"].Data.GetAll(TiingoNews)

                # Accessing unique news via slice event:
                tiingoNews = data.Get(TiingoNews)
            # (returns a dictionary symbol-news, use "Values" to enumerate below)
                for t in tiingoNews.Values:
                    self.Debug(f"Now: {Time} Crawled: {news.CrawlDate} Title: {news.Title}")

|

Historical Data
===============

You can request for historical custom data in your algorithm. To learn more about historical data requests, please visit the Historical Data documentation page. The following example gets Tiingo news historical data using the History API.

.. tabs::

   .. code-tab:: c#

        // Request underlying equity data
        var ibm = AddEquity("IBM", Resolution.Minute).Symbol;
        // Add news data for the underlying IBM asset
        var news = AddData<TiingoNews>(ibm).Symbol;
        // Request 60 days of history with the TiingoNews IBM Custom Data Symbol.
        var history = History<TiingoNews>(news, 60, Resolution.Daily);

   .. code-tab:: py

        # Request underlying equity data.
        ibm = self.AddEquity("IBM", Resolution.Minute).Symbol
        # Add news data for the underlying IBM asset
        news = self.AddData(TiingoNews, ibm).Symbol
        # Request 60 days of history with the TiingoNews IBM Custom Data Symbol
        history = self.History(TiingoNews, news, 60, Resolution.Daily)

|

Data Properties
===============

.. list-table::

   * - **Source** (string)
     - The domain the news source is from.
   * - **CrawlDate** (DateTime)
     - The datetime the news story was added to Tiingos database in UTC. This is always recorded by Tiingo and the news source has no  input on this date.
   * - **Url** (string)
     - URL of the news article.
   * - **PublishedDate** (DateTime)
     - The datetime the news story was published in UTC. This is usually reported by the news source and not by Tiingo. If the news source does not declare a published date, Tiingo will use the time the news story was discovered by our crawler farm.
   * - **Tags** (List<String>)
     - Tags that are mapped and discovered by Tiingo.
   * - **Description** (string)
     - Long-form description of the news story.
   * - **Title** (string)
     - Title of the news article.
   * - **ArticleID** (string)
     - Unique identifier specific to the news article.
   * - **Symbols** (List<Symbol>)
     - What symbols are mentioned in the news story.

|

Analyzing News Sources
======================

Raw text is often analyzed with a technique called Natural Language Processing (NLP). There are many forms of natural language analysis which vary in complexity, but the most simple form is assigning a weighting to individual words and measuring the sum as the sentiment of the text. The demonstration below demonstrates this using Tiingo's News API Data with Python (`C# equivalent <https://www.quantconnect.com/terminal/processCache/?request=embedded_backtest_1b2c1405c098fba455f90222673acadb.html>`_).

.. raw:: html

   <iframe style="border: solid 1px #ebecee; width: 100%; height: 330px" src="https://www.quantconnect.com/terminal/processCache/?request=embedded_backtest_fcbe632d72b4299fa6a6b19eca09e8b4.html"></iframe>

|

Historical Crawl Offset
=======================

News is published, then at a later date it is crawled by Tiingo's technology. These news events are published at the time Tiingo crawls the data to helps prevent look ahead bias.

There can sometimes be large delays between these two dates (e.g. many years). This happens when Tiingo adds a newly discovered source and back-populates their historical archives. Tiingo created their news technology in 2016 so all news prior to then will have a delay between the published and crawl dates.

Once a new source is added, the delay between published to crawl time is typically from a few minutes to an one hour. For this reason, QuantConnect reviews the difference, and if it is greater than one day we set the crawl date to one hour after the publish date. This is an approximation, but it allows for backtesting on historical news with a representative crawl time.

You can control this offset setting with the ``TiingoNews.HistoricalCrawlOffset`` property:

.. tabs::

   .. code-tab:: c#

        // Overriding the new data-source offset to 3 hours.
        TiingoNews.HistoricalCrawlOffset = TimeSpan.FromHours(3);

   .. code-tab:: py

        # Overriding the new data-source offset to 3 hours.
        TiingoNews.HistoricalCrawlOffset = timedelta(0, 3)


|

Personal Live Trading
=====================

To use Tiingo for your personal live trading you will need to purchase a subscription with Tiingo. This is available for individuals for $10 per month, or for businesses for $50 per month. Once you've registered you need to enter your API token to your algorithm as shown below.

.. tabs::

   .. code-tab:: c#

        // Set your authorization code for personal use in Initialize()
        Tiingo.SetAuthCode("....");


   .. code-tab:: py

        # Set your authorization code for personal use in Initialize()
        Tiingo.SetAuthCode("....")

|

Pricing
=======

.. list-table::
   :header-rows: 1

   * - Backtesting
     - Free
   * - Alpha Streams Use, Competitions
     - Free
   * - Personal Paper or Live Trading
     - $10 USD/mo. See `Tiingo Pricing <https://api.tiingo.com/about/pricing>`_

|

About the Provider
==================

.. figure:: https://cdn.quantconnect.com/competitions/i/email/tiingo_logo_rev0.PNG
   :width: 200
   :align: right

`Tiingo News API <https://api.tiingo.com/products/news-api>`_ goes beyond traditional news sources and focuses on finding rich, quality content written by knowledgeable writers. Tiingo's proprietary algorithms scan unstructured, non-traditional news and other information sources while using proprietary algorithms to tag companies, topics, and assets. This refined system is backed by over ten years of research and development, and is written by former institutional quant traders. Because of this dedicated approach, Tiingo's News API is a trusted tool used by quant funds, hedge funds, pension funds, social media companies, and tech companies around the world.

