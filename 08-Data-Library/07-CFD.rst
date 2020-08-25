.. _data-library-cfd:

===
CFD
===

|

Introduction
============

QuantConnect serves 51 CFD contracts from OANDA, starting on various dates from April 2004. CFD live-trading is only available for non-US residents. Quotes data with tick, second, minute, hour, and daily resolutions are available.

|

OANDA Brokerage CFD Data
========================

QuantConnect provides all OANDA Brokerage CFD contracts for backtesting and trading.

+-----------------------------------------------------------------------------------------+
| Data Properties                                                                         |
+=====================+===================================================================+
| **Data Provider**   | `AlgoSeek <https://www.quantconnect.com/data/provider/algoseek>`_ |
+---------------------+-------------------------------------------------------------------+
| **Start Date**      | Data is available starting May 1st, 2009                          |
+---------------------+-------------------------------------------------------------------+
| **Symbol Universe** | ≈ 1100 Symbols. All symbols trading on future exchanges each day. |
+---------------------+-------------------------------------------------------------------+
| **Data Type**       | Trades and Quotes                                                 |
+---------------------+-------------------------------------------------------------------+
| **Resolutions**     | Tick, Second, Minute                                              |
+---------------------+-------------------------------------------------------------------+

Future data comes as trades and quotes, with tick, second, and minute resolutions available.

Live Trading futures data is sourced from Interactive Brokers. The data used comes from your futures data subscription. You must have a valid `data subscription <https://www.interactivebrokers.com/en/software/am/am/manageaccount/marketdatasubscriptions.htm>`_ with Interactive Brokers.

Requesting Futures Data
=======================

To subscribe to futures data in QuantConnect, you should first select the asset you'd like to trade and then specify the filter by the contract's expiration that you'd like to see.

.. code-block:: c#

    // Complete Add Future API - Including Default Parameters:
    AddFuture(string symbol,
              Resolution resolution = Resolution.Minute,
              string market = null,
              bool fillDataForward = true,
              decimal leverage = 0m)

By default, the futures universe is filtered down to contracts that expire within 35 days. A different set of contracts can be chosen with the ``SetFilter`` method:

.. tabs::

   .. code-tab:: c#

        var future = AddFuture(Futures.Indices.SP500EMini);
        future.SetFilter(TimeSpan.FromDays(30), TimeSpan.FromDays(180));

   .. code-tab:: py

        future = self.AddFuture(Futures.Indices.SP500EMini)
        future.SetFilter(timedelta(30), timedelta(182))

We can also use filter functions on the contract list to select the contract(s) we want to trade:

.. tabs::

   .. code-tab:: c#

        // In Initialize
        var future = AddFuture(Futures.Indices.SP500EMini, Resolution.Minute);
        future.SetFilter(TimeSpan.Zero, TimeSpan.FromDays(182));
        // or filtering with Linq:
        future.SetFilter(universe => universe.Expiration(TimeSpan.Zero, TimeSpan.FromDays(182)));

   .. code-tab:: py

        # In Initialize
        future = self.AddFuture(Futures.Indices.SP500EMini, Resolution.Minute)
        future.SetFilter(timedelta(0), timedelta(182))
        # or with a Lambda Function:
        future.SetFilter(lambda universe: universe.Expiration(timedelta(0), timedelta(182)))

Using Futures Data
==================

Quotes and trades for your selected future contracts can be accessed in the Slice object in the OnData event handler. The ``FuturesChains`` member contains a ``FutureChain`` object for each subscribed future. A FutureChain is a collection of individual future contracts with different expiry dates.

.. tabs::

   .. code-tab:: c#

        // Save accessor symbol in Initialize() function.
        futureSymbol = future.Symbol;

        // In OnData(Slice slice)
        FuturesChain chain;
        // Explore the future contract chain
        if (slice.FuturesChains.TryGetValue(futureSymbol, out chain))
        {
            var underlying = chain.Underlying;
            var contracts = chain.Contracts.Value;
            foreach (var contract in contracts)
            {
                //
            }
        }

   .. code-tab:: py

        # Explore the future contract chain
        def OnData(self, slice):
            for chain in slice.FutureChains.Values:
                contracts = chain.Contracts
                for contract in contracts.Values:
                    pass

Future contracts have the following properties:

.. tabs::

   .. code-tab:: c#

        public class FuturesContract
        {
            Symbol Symbol;
            Symbol UnderlyingSymbol;
            DateTime Expiry;
            DateTime Time;
            decimal OpenInterest;
            decimal LastPrice;
            long Volume;
            decimal BidPrice;
            long BidSize;
            decimal AskPrice;
            long AskSize;
        }

   .. code-tab:: py

        class FuturesContract:
            self.Symbol # (Symbol) Symbol for contract needed to trade.
            self.UnderlyingSymbol # (Symbol) Underlying futures asset.
            self.Expiry # (datetime) When the future expires
            self.OpenInterest # (decimal) Number of open interest.
            self.LastPrice # (decimal) Last sale price.
            self.Volume # (long) reported volume.
            self.BidPrice # (decimal) bid quote price.
            self.BidSize # (long) bid quote size.
            self.AskPrice # (decimal) ask quote price.
            self.AskSize # (long) ask quote size.

|

Timezone
========

Algoseek futures data is set in the timezone in which the future is listed. The futures listed in CME or CBOT have their data set in Chicago Time, and the futures listed in NYMEX and Comex have their data set in New York Time. So when accessing futures data, make sure to account for the different time zones.

|

About the Provider
==================

.. figure:: https://cdn.quantconnect.com/web/i/providers/algoseek.png
   :width: 200
   :align: center

`AlgoSeek <https://www.algoseek.com/>`_ is a leading provider of historical intraday US market data to banks, hedge funds, academia, and individuals worldwide. Their high quality and affordable datasets are used for research and trading around the world.

AlgoSeek has been collecting US Equities and ETF data on all listed USA equities and ETFs since January 2007. Their data is ready for institutional researchers for backtesting and quant research. Data is timestamped to the millisecond.