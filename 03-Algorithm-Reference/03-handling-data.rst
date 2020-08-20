.. _algorithm-reference-handling-data:

===================================
Algorithm Reference - Handling Data
===================================

|

Demonstration Algorithms
========================

.. list-table::
   :header-rows: 1

   * - C#
     - Python
   * - `DelistingEventsAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/DelistingEventsAlgorithm.cs>`_
     - `DelistingEventsAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/DelistingEventsAlgorithm.py>`_
   * - `DividendAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/DividendAlgorithm.cs>`_
     - `DividendAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/DividendAlgorithm.py>`_

|

Introduction
============

Requested data is passed into event handlers for you to use to make trading decisions. The primary event handler, Slice, groups all data types together at a single moment in time in the ``OnData(Slice data)`` handler. Slice is short for "time slice" - representing a slice of time and values of the data at that time. C# and F# also allow you to receive data with dedicated event handlers for each data type e.g ``OnData(TradeBars data)``.

All data uses ``DataDictionary`` objects to group data by symbol and provide easy access to information. The plural of the type denotes the collection of objects (e.g., the TradeBars DataDictionary is made up of TradeBar objects; QuoteBars DataDictionary is made up of QuoteBar objects). You can access individual data points in the dictionary through its string or symbol dictionary index. For example ``ibmTradeBar = slice.Bars["IBM"]`` (Python) / ``var ibmTradeBar = slice.Bars["IBM"]`` (C#).

|

Time Slices
===========

The Slice event handler combines all of the data into a single method. It represents the data at a point in time. The Slice object contains many helpers for accessing your data. The ``Slice`` objects arrive to the ``OnData(Slice data)`` event handler.

The ``Slice`` object gives you three ways to access your data:

* Dynamic string/symbol indexer, which returns a dynamic object of your type slice["IBM"].
* Statically typed properties (slice.Bars[], slice.QuoteBars[]).
* Statically typed Get<T>() helper

Strongly typed access gives you compile-time safety, but dynamic types can sometimes simplify coding. We recommend static types as they are easier to debug.

Slice has the following internal structure:

.. tabs::

   .. code-tab:: c#

        class Slice : IEnumerable<KeyValuePair<Symbol, BaseData>>
        {
            TradeBars Bars;
            QuoteBars QuoteBars;
            Ticks Ticks;
            OptionChains OptionChains;
            FuturesChains FuturesChains;
            Splits Splits;
            Dividends Dividends;
            Delistings Delistings;
            SymbolChangedEvents SymbolChangedEvents;
        }

   .. code-tab:: py

        class Slice:
            TradeBars Bars;
            QuoteBars QuoteBars;
            Ticks Ticks;
            OptionChains OptionChains;
            FuturesChains FuturesChains;
            Splits Splits;
            Dividends Dividends;
            Delistings Delistings;
            SymbolChangedEvents SymbolChangedEvents;

It contains all the data for a given moment in time. TradeBars and QuoteBars are symbol/string indexed dictionaries so you can easily access the data. Ticks is a list of ticks for that moment of time, indexed by the symbol. To make accessing the data easier the Slice object itself can also be indexed. E.g. ``slice["IBM"]`` will return a TradeBar for IBM, and ``slice["EURUSD"]`` will return a QuoteBar for EURUSD. TradeBars are supported for Equity, Options, and Future asset types; QuoteBars are supported for Forex, CFD, and Future asset types.

Since the Slice object is indexed, it is possible to check if the time slice contains specific data. e.g. ``slice.ContainsKey("EURUSD")`` will return a boolean. Even if requesting data to be filled forward, you should check if the data exists in the dictionary first. If there is little trading, or you are in the same time loop as when you added the security, it may not have any data.

|

Other Event Handlers
====================

C#
--

In C#/F# data is also piped into dedicated event handlers for each data type. To use data this way, you need to put an event handler in your algorithm that follows the pattern: ``public void OnData(TradeBars data) {}``. LEAN automatically detects the method exists and sends data to it.

.. tabs::

   .. code-tab:: c#

        public void OnData(TradeBars data) {
            // TradeBars objects are piped into this method.
        }
        public void OnData(Ticks data) {
            // Ticks objects are piped into this method.
        }

Python
------

Python passes all data events into the ``def OnData(self, slice)``: event handler. This is the preferred way to access data for your strategy. This includes all the data you've requested for your algorithm, including custom data.

|

Data Formats
============

There are seven financial data types: Tick, TradeBar, QuoteBar, Delisting, SymbolChangedEvent, Split, and Dividend. All data extends from ``BaseData``, the core data class, which provides Symbol, Time, and Value properties.

|

Ticks
=====

``Ticks`` data provides LastPrice and Quantity properties for a given time. If it is a quote tick, it also contains non-zero BidPrice, BidSize, AskPrice, and AskSize properties. A Trade Tick is a record of a transaction or sale for the security. A Quote Tick is a bid or offer to purchase the security for a specific price. For equities, all of the ticks for given second are grouped together in backtesting. In live trading, ticks are streamed directly to your algorithm as soon as they occur. Data with millisecond resolution timestamps (Forex, CFD, and Futures) generally only have 1 tick in their list, but when multiple trades occur within a millisecond they may also be grouped together.

.. tabs::

   .. code-tab:: py

        self.AddEquity("IBM", Resolution.Tick) ## Subscribe to tick-level IBM data

        def OnData(self, data):

             ## Use the [-1] indexer to access to most recent tick that arrived
             self.Debug(f"Last price: {data['IBM'][-1].LastPrice}")
             self.Debug(f"Last price: {data['IBM'][-1].Quantity}")

Tick data is raw and unfiltered. It may contain bad ticks which skew your trade results. We recommend only using tick data if you understand the risks and are able to perform your own online tick filtering. Ticks which QuantConnect believes are suspicious are marked with the boolean ``Suspicious`` flag.

|

TradeBars
=========

TradeBars are individual trades from the exchanges consolidated into price bars. The ``TradeBar`` provides Open, High, Low, Close, and Volume properties for a given period of time. TradeBars are only supported for Equity, Options, and Futures asset types (NOT Forex or CFD).

.. figure:: https://cdn.quantconnect.com/docs/i/dataformat-tradebar.png

.. tabs::

   .. code-tab:: py

        self.AddEquity("IBM", Resolution.Hour) ## Subscribe to hourly TradeBars

         def OnData(self, data):
            ## You can access the TradeBar dictionary in the slice object and then subset by symbol
            ## to get the TradeBar for IBM
            tradeBars = data.Bars
            ibmTradeBar = tradeBars['IBM']
            ibmOpen = ibmTradeBar.Open      ## Open price
            ibmClose = ibmTradeBar.Close    ## Close price

            ## Or you can access the IBM TradeBar by directly subsetting the slice object
            ## (since you are subscribed to IBM equity data, this will return a TradeBar rather than a QuoteBar)
            ibmOpen = data['IBM'].Open         ## Open price
            imbClose = data['IBM'].Close       ## Close price

|

QuoteBars
=========

QuoteBars are built by consolidating the bid and ask ticks from the exchanges into bars. The ``QuoteBar`` provides Open, High, Low, Close, Bid, Ask, LastBidSize, and LastAskSize properties for a given period of time. The Bid and the Ask properties are ``Bar`` objects that contain Open, High, Low, and Close. The QuoteBar Open, High, Low, and Close properties values are the mean of the respective Bid and Ask properties. QuoteBars are supported for all asset types.

.. figure:: https://cdn.quantconnect.com/docs/i/dataformat-quotebar.png

.. tabs::

   .. code-tab:: py

        self.AddForex('EURUSD', Resolution.Hour) # Subscribe to hourly QuoteBars in Initialize(self)

         def OnData(self, data):
            ## You can access the EURUSD QuoteBar directly by subsetting the slice object
            fxOpen = data['EURUSD'].Open          ## Market Open FX Rate
            fxClose = data['EURUSD'].Close        ## Market Close FX Rate

            ## If you are subscribed to more than one Forex or Futures data stream then you can
            ## access the QuoteBar dictionary and then subset this for your desired Forex symbol
            fxQuoteBars = data.QuoteBars
            eurusdQuoteBar = fxQuoteBars['EURUSD']     ## EURUSD QuoteBar
            fxOpen = eurusdQuoteBar.Open               ## Market Open FX Rate
            fxClose = eurusdQuoteBar.Close             ## Market Close FX Rate

With a specific QuoteBar, you can also access Bid and Ask Bars for the same security. These Bars provide information specific to the Bid and Ask side of Forex and Future asset types, while the QuoteBar.Open, High, Low, and Close properties are the midpoint of the Bid-Ask spread at that moment of time. These QuoteBar.Bid and QuoteBar.Ask bars have Open, High, Low, and close properties (e.g., ``QuoteBar.Bid.Open``).

|

Dividends
=========

``Dividend`` events are triggered on payment of a dividend. It provides the Distribution per share.

.. tabs::

   .. code-tab:: py

        def Initialize(self):
            self.SetStartDate(2017, 6, 1)
            self.SetEndDate(2017, 6, 28)
            self.spy = self.AddEquity("SPY", Resolution.Hour)

        def OnData(self, data):
            if not self.Portfolio.Invested:
                self.Buy("SPY", 100)

            ## Condition to see if SPY is in the Dividend DataDictionary
            if data.Dividends.ContainsKey("SPY"):
                ## Log the dividend distribution
                self.Log(f"SPY paid a dividend of {data.Dividends['SPY'].Distribution}")

|

Splits
======

``Split`` events are triggered on a share split or reverse split event. It provides a SplitFactor and ReferencePrice.

.. tabs::

   .. code-tab:: py

        def Initialize(self):
            self.SetStartDate(2003, 2, 1)
            self.SetEndDate(2003, 2, 28)
            self.SetCash(100000)
            self.msft = self.AddEquity("MSFT", Resolution.Daily)
            self.msft.SetDataNormalizationMode(DataNormalizationMode.Raw)

        def OnData(self, data):
            if not self.Portfolio.Invested:
                self.Buy("MSFT", 100)

            ## If MSFT had a split, print out information about it
            if data.Splits.ContainsKey("MSFT"):
                ## Log split information
                spySplit = data.Splits['MSFT']
                if spySplit.Type == 0:
                    self.Log('MSFT stock will split next trading day')
                if spySplit.Type == 1:
                    self.Log("Split type: {0}, Split factor: {1}, Reference price: {2}".format(spySplit.Type, spySplit.SplitFactor, spySplit.ReferencePrice))

|

SymbolChangedEvent
==================

``SymbolChangedEvents`` provides notice of new ticker names for stocks, or mergers of two tickers into one. It provides the OldSymbol and NewSymbol tickers.

.. tabs::

   .. code-tab:: py

        def Initialize(self):
            self.SetStartDate(2014, 4, 1)
            self.SetEndDate(2014, 4, 3)
            self.SetCash(100000)
            self.goog = self.AddEquity("GOOG", Resolution.Daily)

        def OnData(self, data):
            self.MarketOrder('GOOG', 10)

            ## Log old and new symbol if 'GOOG' symbol has changed
            if data.SymbolChangedEvents.ContainsKey('GOOG'):
                self.Log("Old symbol: {0}, New symbol: {1}".format(data.SymbolChangedEvents['GOOG'].OldSymbol,data.SymbolChangedEvents['GOOG'].NewSymbol))

|

Delistings
==========

``Delisting`` events provide notice that an asset is no longer trading on the exchange. A delisting warning is issued on the final trading day for a stock delisting event to give your algorithm time to gracefully exit out of positions before forced termination.

.. tabs::

   .. code-tab:: py

        def Initialize(self):
            self.SetStartDate(2007, 5, 16)
            self.SetEndDate(2007, 5, 25)
            self.SetCash(100000);
            equity = self.AddEquity("AAA", Resolution.Daily)

        def OnData(self, data):
            self.MarketOrder('AAA', 10)

            ## Print delisting warnings and noritifications
            if data.Delistings.ContainsKey('AAA'):
                delisting = data.Delistings['AAA']

                ## Log the delisting warning type
                self.Log(delisting.ToString())

                if delisting.Type == 0:
                    self.Log('AAA will be delisted EOD')
                if delisting.Type == 1:
                    self.Log('AAA delisted')