.. _data-library-options:

=======
Options
=======

|

Introduction
============

QuantConnect provides US options trade and quote price data from the OPRA feed. This includes approximately 4000 symbols, each of which has roughly 10 strikes on average. For speed options, data is filtered to only load the data you want for your algorithm. You can set this filter with your code or leave it open to pull in all strikes and expiry dates for a given symbol.

.. list-table:: Data Properties
   :header-rows: 0

   * - Data Provider
     - `AlgoSeek <https://www.quantconnect.com/data/provider/algoseek>`_

   * - Start Date
     - Data is available starting January 1st, 2010

   * - Symbol Universe
     - ≈ 4000 Symbols. All symbols trading on the CBOE each day.

   * - Data Type
     - Trades and Quotes

   * - Resolutions
     - Minute

The source options tick data is converted into quote and trade bars at Minute resolution. No other resolutions are hosted.

Live trading Options data is sourced from Interactive Brokers directly. This is from *your* data subscription. In order to do live options trading, you must have a valid `data subscription <https://www.interactivebrokers.com/en/software/am/am/manageaccount/marketdatasubscriptions.htm>`_ with Interactive Brokers.

See `AlgoSeek Options <https://www.algoseek.com/options>`_ for more information.

|

Requesting Options Data - Universe
==================================

To subscribe to QuantConnect options data, you can use the AddOption method:

.. code-block::

    // Complete Add Option API - Including Default Parameters:
    AddOption(string underlying,
          Resolution resolution = Resolution.Minute,
          string market = null,
          bool fillDataForward = true,
          decimal leverage = 0m)

By default, the option universe is filtered down to contracts that expire within 35 days, one contract below and another above ATM, and exclude weekly. A different set of contracts can be chosen with the ``SetFilter`` method:

.. tabs::

   .. code-tab:: c#

        // In Initialize
        var option = AddOption("GOOG");
        option.SetFilter(-2, 2, TimeSpan.Zero, TimeSpan.FromDays(182));
        // or Linq
        option.SetFilter(universe => from symbol in universe
                .WeeklysOnly()
                .Expiration(TimeSpan.Zero, TimeSpan.FromDays(10))
                where symbol.ID.OptionRight != OptionRight.Put &&
                universe.Underlying.Price - symbol.ID.StrikePrice < 60
                select symbol);

   .. code-tab:: py

        # In Initialize
        option = self.AddOption("GOOG");
        option.SetFilter(-2, 2, timedelta(0), timedelta(182))
        # or Lambda
        option.SetFilter(lambda universe: universe.WeeklysOnly().Strikes(-2, +2).Expiration(timedelta(0), timedelta(182)))

The data defaults to monthly expiration contracts. If you'd like weekly expirations as well, you must add it to your filter in the ``SetFilter`` method. ``SetFilter`` also accepts Linq expressions in C#.

.. tabs::

   .. code-tab:: c#

        var option = AddOption("GOOG");
        option.SetFilter(universe => from symbol in universe.IncludeWeeklys()
            .Expiration(TimeSpan.Zero, TimeSpan.FromDays(10))
            where symbol.ID.OptionRight != OptionRight.Put && universe.Underlying.Price - symbol.ID.StrikePrice < 60 select symbol);

   .. code-tab:: py

        option = self.AddOption("GOOG");
        option.SetFilter(lambda universe: universe.IncludeWeeklys().Strikes(0, 10).Expiration(timedelta(0), timedelta(182)))

|

Requesting Options Data - OptionChainProvider
=============================================

Another way to subscribe to options data is by using OptionChainProvider. The GetOptionContractList method of OptionChainProvider returns a list of options contracts exclusively for the specified symbol and date.

.. code-block::

        OptionChainProvider.GetOptionContractList(Symbol symbol,
                        DateTime date)

This method allows you to filter for options contracts based on your algorithm's specific requirements. Manual filtering these contracts is limited to the information included in the Symbol (strike, expiration, type, style) and/or prices from a History call. Once you have applied filters, you can use AddOptionContract to add the desired contract(s) to the Universe. This process allows you to subscribe exclusively to the options contracts you want and helps maintain a small Universe to keep your algorithm running as fast as possible.

.. code-block::

            AddOptionContract(Symbol symbol,
                            Resolution resolution = Resolution.Minute,
                            bool fillforward = true,
                            decimal Leverage = 0m)

In backtesting, OptionChainProvider.GetOptionContractList allows you to request a list of options contracts for a specific symbol and date. In Live Mode, the date argument is fixed to the current algorithm time.

.. tabs::

   .. code-tab:: c#

        // In public class
            private Symbol _equitySymbol;

            // In Initialize
            var equity = AddEquity("GOOG", Resolution.Minute);
            equity.SetDataNormalizationMode(DataNormalizationMode.Raw);
            _equitySymbol = equity.Symbol;

        public override void OnData(Slice data)
        {
            // Get list of Options Contracts for a specific time
            var contracts = OptionChainProvider.GetOptionContractList(_equitySymbol, data.Time);

            // use AddOptionContract() to subscribe the data for specified contract
            AddOptionContract(contracts.First(), Resolution.Minute);
        }

   .. code-tab:: py

        # In Initialize
            self.equity = self.AddEquity("GOOG", Resolution.Minute)
            self.equity.SetDataNormalizationMode(DataNormalizationMode.Raw)

        def OnData(self, data):
            ## Call options filter
            contract = self.OptionsFilter(data)

        ## Example of a filtering function to be called
        def OptionsFilter(self, data):
            contracts = self.OptionChainProvider.GetOptionContractList(self.equity.Symbol, data.Time)  ## Get list of Options Contracts for a specific time

            ## Use AddOptionContract() to subscribe the data for specified contract
            self.AddOptionContract(contracts[0], Resolution.Minute)  ## Add the first contract in contracts

            return contracts[0]

|

Using Options Data
==================

Options quote and trade data can be accessed in the Slice object in the OnData event handler. The ``OptionChains`` member contains an ``OptionChain`` object for each subscribed option. An ``OptionChain`` object has information about the underlying asset and options contracts that were filtered by ``SetFilter``.

.. tabs::

   .. code-tab:: c#

        var underlying = chain.Underlying;
        var contracts = chain.Contracts;

   .. code-tab:: py

        underlying = chain.Underlying
        contracts = chain.Contracts

.. tabs::

   .. code-tab:: c#

        // In Initialize
        OptionSymbol = option.Symbol;

        // In OnData
        OptionChain chain;
        if (slice.OptionChains.TryGetValue(OptionSymbol, out chain))
        {
            // we find at the money (ATM) put contract with farthest expiration
            var atmContract = chain
                .OrderByDescending(x => x.Expiry)
                .ThenBy(x => Math.Abs(chain.Underlying.Price - x.Strike))
                .ThenByDescending(x => x.Right)
                .FirstOrDefault();
        }

   .. code-tab:: py

        # In Initialize
        self.OptionSymbol = option.Symbol;

        # In OnData(self, slice)
        for chain in slice.OptionChains.Values:
        # sort contracts to find at the money (ATM) contract with the farthest expiration
            contracts = sorted(sorted(chain, \
                           key = lambda x: abs(chain.Underlying.Price - x.Strike)), \
                           key = lambda x: x.Expiry, reverse=True)

An ``OptionChain`` is a list of ``OptionContract`` objects. The OptionContract has the following additional properties:

.. tabs::

   .. code-tab:: c#

        // List of OptionContract objects
        class OptionChain : BaseData, IEnumerable<OptionContract> {
            BaseData Underlying;  // Symbol of underlying asset.
            QuoteBars QuoteBars; // All quotebars in this chain.
            OptionContracts Contracts; // All tradebars in this chain.
        }

   .. code-tab:: py

        # List of OptionContract objects
        class OptionChain(self):
            self.Underlying  # Symbol of underlying asset.
            self.QuoteBars   # All quotebars in this chain.
            self.Contracts   # All tradebars in this chain.

The ``OptionContract`` object is the tradeable security of options markets. It has its own Symbol code representing the unique option contract. It has the following properties:

.. tabs::

   .. code-tab:: c#

        // Tradable Option Contract From Option Chain for Asset
        class OptionContract : BaseData, IEnumerable<OptionContract>{
            Symbol Symbol;    // Symbol of tradable asset.
            Symbol UnderlyingSymbol;    // Symbol of underlying asset.
            decimal Strike; // Strike price for contract.
            DateTime Expiry; // Expiry date for the contract.
            OptionRight Right; // Put or Call
            decimal TheoreticalPrice; //Price generated from option model.
            decimal ImpliedVolatility; //Implied volatility from option model.
            Greeks Greeks; //collection of greek properties
            DateTime Time; //Time of the data.
            decimal OpenInterest; //Number of contracts available.
            decimal LastPrice; //Last Trade Price
            long Volume; //Number of contracts traded this minute.
            decimal BidPrice; //Bid price.
            long BidSize; //Bid Size
            decimal AskPrice; //Asking Price
            long AskSize; //Ask Size.
            decimal UnderlyingLastPrice; //Underlying price of asset.
        }

   .. code-tab:: py

        # Tradable Option Contract From Option Chain for Asset
        class OptionContract(self):
            self.Symbol    # Symbol of tradable asset.
            self.UnderlyingSymbol    # Symbol of underlying asset.
            self.Strike     # (decimal) strike price for contract.
            self.Expiry     # (datetime) expiry date for the contract.
            self.Right      # (OptionRight) Put or Call
            self.TheoreticalPrice # (decimal) Price generated from option model.
            self.ImpliedVolatility   # (decimal) Implied volatility from option model.
            self.Greeks     # (Greeks) collection of greek properties
            self.Time       # (datetime) Time of the data.
            self.OpenInterest # (decimal ) Number of contracts available.
            self.LastPrice  # (decimal) Last Trade Price
            self.Volume     # Number of contracts traded this minute.
            self.BidPrice   # (decimal) Bid price.
            self.BidSize    # (long) Bid Size
            self.AskPrice   # (decimal) Asking Price
            self.AskSize    # (long) Ask Size.
            self.UnderlyingLastPrice # (decimal) Underlying price of asset.

|

Timezone
========

Algoseek options data is set in New York Time. This means that when accessing options data, all data will be time stamped in New York Time.

|

About the Provider
==================

.. figure:: https://cdn.quantconnect.com/web/i/providers/algoseek.png
   :width: 200
   :align: center

`AlgoSeek <https://www.algoseek.com/>`_ is a leading provider of historical intraday US market data to banks, hedge funds, academia, and individuals worldwide. Their high quality and affordable datasets are used for research and trading around the world.

AlgoSeek has been collecting US Equities and ETF data on all listed USA equities and ETFs since January 2007. Their data is ready for institutional researchers for backtesting and quant research. Data is timestamped to the millisecond.