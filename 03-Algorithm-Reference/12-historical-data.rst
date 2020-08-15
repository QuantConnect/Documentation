=====================================
Algorithm Reference - Historical Data
=====================================

|

Introduction
============

QuantConnect allows you to request historical data in many different formats. In this section, we'll cover each of these formats and how you can use them in your algorithm. Broadly, there are two ways to use these data requests: direct historical data requests and indirect algorithm warm up.

A direct historical data request fetches any historical data you request at any time for a given `Symbol <https://www.quantconnect.com/docs/key-concepts/security-identifiers>`_. This gives you the most control to apply the data as you need. Indirect algorithm warm-up winds back the start time of your algorithm and feeds it with historical data, simulating a real data feed. Warm-up is best for quickly restoring the state of a fixed set of assets.

Below we'll explore these two options and how you can use them in your algorithm.

|

Key History Concepts
====================

The QuantConnect historical data API has many different options to give you the greatest flexibility in how to apply it to your algorithm. Over the years it has evolved to handle different data formats, data resolutions, and use cases, but we have always strived to keep two constants to its design:

.. note::  Key Concept #1: Request Options

           * History requests can be done by a bar count; or,
           * History requests can be done for a period of time.

Secondly, when placing a history request, it's important to consider what data will be returned as each asset type comes in slightly different data shapes. All python history requests return a Data Frame, which has different columns depending on the type of data requested. Data is returned as TradeBars, QuoteBars, or Slice objects depending on how you request it and the data available for your security.

.. note::  Key Concept #2: Return Format

           * History requests with symbols provided return data as a pandas data frame.
           * History requests without symbols provided fetch history for the entire universe of your securities, and return it as an array of ``Slice`` objects.

Finally, when reviewing the results of your history requests, remember they are indexed by the EndTime of each data point. For daily data, this results in data points appearing on Saturday and skipping Monday's bar.

.. note::  Key Concept #3: Time Index

           * History results are indexed by the `EndTime <https://www.quantconnect.com/docs/key-concepts/understanding-time#Understanding-Time-Ticks-Bars-and-the-Time-Frontier>`_ of a bar.

This can seem a little complex at first, but we'll step through each of these in the sections below.

|

History Request For Known Symbols
=================================

The simplest form of history request is for a known set of Symbols. This is common for fixed universes of securities, or when you'd like to prepare new securities added to your algorithm.

History data is returned in an ascending order from oldest to newest. This is required for piping the data into indicators to perform warm-up.

History requests for a known set of Symbols return a Data Frame. Each row of the Data Frame represents the prices at a point of time. Each *column* of the Data Frame is a property of that price data (e.g. open, high, low, close).

**Single Symbol Request Examples**

History API calls follow the following pattern: ``self.History(symbol, bar_count, resolution = null)``

.. tabs::

   .. code-tab:: c#

        // Single Symbol History Method Arguments:
        var bars = History<Type>(Symbol symbol, int barCount, Resolution resolution = null);
        var bars = History<Type>(Symbol symbol, TimeSpan period, Resolution = null);

   .. code-tab:: py

        # EXAMPLE 1: Requesting By Bar Count: 5 IBM TradeBars, defaulting to security resolution:
        self.AddEquity("IBM", Resolution.Daily)
        self.df = self.History(self.Symbol("IBM"), 5)

.. figure:: https://cdn.quantconnect.com/docs/i/history-dataframe-tradebars-single_rev0.png

.. tabs::

   .. code-tab:: c#

        // EXAMPLE 1: 100 Bars of Single Symbol, Specifying Type, Default to Security Resolution:
        var ibm = AddEquity("IBM", Resolution.Minute).Symbol;
        var bars = History<TradeBar>(ibm, 100);

        // Same request but for QuoteBars
        var eurusd = AddForex("EURUSD", Resolution.Minute).Symbol;
        var quoteBars = History<QuoteBar>(eurusd, 100);

   .. code-tab:: py

        # EXAMPLE 2: Requesting By Bar Count: 5 IBM Minute TradeBars:
        self.df = self.History(self.Symbol("IBM"), 5, Resolution.Minute)

.. figure:: https://cdn.quantconnect.com/docs/i/history-dataframe-tradebars-single-minute_rev0.png

.. tabs::

   .. code-tab:: c#

        // EXAMPLE 2: Six Hours of Bars of Single Symbol, Setting Resolution:
        var ibm = AddEquity("IBM", Resolution.Minute).Symbol;
        var bars = History<TradeBar>(ibm, TimeSpan.FromHours(6), Resolution.Minute);

        // Same request but for QuoteBars
        var eurusd = AddForex("EURUSD", Resolution.Minute).Symbol;
        var quoteBars = History<QuoteBar>(eurusd, TimeSpan.FromHours(6), Resolution.Minute);

   .. code-tab:: py

        # EXAMPLE 3: Requesting By Period: 1 Week IBM TradeBars, defaulting to security resolution:
        self.df = self.History(self.Symbol("IBM"), timedelta(7))

        # Imporant Note: April 19th is Easter Friday, which has a bar EndTime = 20th, is not present.

.. figure:: https://cdn.quantconnect.com/docs/i/history-dataframe-period-daily_rev0.png

.. tabs::

   .. code-tab:: py

        # EXAMPLE 4: Requesting By Period: 5 Minutes IBM TradeBars:
        self.df = self.History(self.Symbol("IBM"), timedelta(5), Resolution.Minute)

        # Important Note: Period history requests are relative to "now" algorithm time. The example above would return 5 minute bars if requested *at* market close. If you wait for 16.05 it will return nothing.

.. figure:: https://cdn.quantconnect.com/docs/i/history-stacked-multi-symbol-python_rev0.png

**Multiple Symbol Request Examples**

To request history for multiple symbols at a time, you need to pass an array of Symbol objects to the same API methods as above.

Multi-Symbol History API calls follow the following pattern: ``self.History( symbols[], bar_count, resolution = null )``

.. tabs::

   .. code-tab:: py

        # EXAMPLE 5: Multi-Symbol History Request.

        self.df = self.History([self.Symbol("IBM"), self.Symbol("AAPL")], 2)

.. figure:: https://cdn.quantconnect.com/docs/i/history-stacked-multi-symbol-python_rev0.png

Assumed Default Values

*   Resolution: LEAN attempts to guess the resolution you request by looking at any securities you already have in your algorithm. If you have a matching Symbol, QuantConnect will use the same resolution. When no default values can be located ``Resolution.Minute`` is selected.

|

All Securities History Request
==============================

With the QuantConnect History API, you can request history for all active securities in your universe. The parameters are very similar to other history methods, but the return type is an array of ``Slice`` objects. This has the same properties as the OnData() Slice object.

The `Slice <https://www.quantconnect.com/docs/algorithm-reference/handling-data#Handling-Data-Time-Slices>`_ object holds all of the results in a sorted enumerable collection you can iterate over with a foreach loop.

.. tabs::

   .. code-tab:: c#

        // EXAMPLE 1: Requesting 5 Bars For All Securities, default to security resolution:

        // Setting Up Universe
        AddEquity("IBM", Resolution.Daily)
        AddEquity("AAPL", Resolution.Daily)

        // Request history data and enumerate results:
        var slices = History(5)
        foreach (var s in slices) {
            Debug($"{s.Time} AAPL: {s.Bars["AAPL"].Close} IBM: {s.Bars["IBM"].Close}");

   .. code-tab:: py

        # EXAMPLE 1: Requesting 5 Bars For All Securities, default to security resolution:

        # Setting Up Universe
        self.AddEquity("IBM", Resolution.Daily)
        self.AddEquity("AAPL", Resolution.Daily)

        # Request history data and enumerate results:
        slices = self.History(5)
        for s in slices:
            print(str(s.Time) + \
                  " AAPL:" + str(s.Bars["AAPL"].Close) + " IBM:" + str(s.Bars["IBM"].Close))

.. figure:: https://cdn.quantconnect.com/docs/i/history-all-security-slices_rev0.png

.. tabs::

   .. code-tab:: c#

        // EXAMPLE 2: Requesting 24 Hours of Hourly Data For All Securities:

        var slices = History(TimeSpan.FromHours(24), Resolution.Hour);
        foreach (var s in slices) {
             Debug($"{s.Time} AAPL: {s.Bars["AAPL"].Close} IBM: {s.Bars["IBM"].Close}");
        }

        // Keep in mind you TimeSpan history requests are relative to "now" in Algorithm Time. If you requested this data on a Monday morning, it would return an empty array because the market was closed over the weekend.

   .. code-tab:: py

        # EXAMPLE 2: Requesting 5 Minutes For All Securities:

        slices = self.History(timedelta(minutes=5), Resolution.Minute)
        for s in slices:
            print(str(s.Time) + \
                  " AAPL:" + str(s.Bars["AAPL"].Close) + " IBM:" + str(s.Bars["IBM"].Close))
        # Keep in mind your timedelta history requests are relative to "now" in Algorithm Time. If you requested this data at 16.05, it would return an empty array because the market was closed.

.. figure:: https://cdn.quantconnect.com/docs/i/history-all-security-slices-minute_rev0.png

|

Working with Data Frames
========================

|

History Data Formats
====================

The QuantConnect platform hosts a specific set of data so the history data is limited to a few specific formats. See the table below for a guide to the format of data by security type:

.. list-table::
   :widths: 25 50
   :header-rows: 1

   * - Security Type
     - Supported Types

   * - Equity
     - TradeBar

       Tick, Second, Minute, Hour, Daily

   * - Forex, CFD
     - QuoteBar

       Tick, Second, Minute, Hour, Daily

   * - Crypto
     - TradeBar (default), QuoteBar (available)

       Tick, Second, Minute, Hour, Daily

   * - Future Contracts
     - TradeBar (default), QuoteBar (available)

       Tick, Second, Minute

   * - Option Contracts
     - TradeBar (default), QuoteBar (available)

       Minute

History is returned in TradeBars by default, but for Futures, Crypto and Options QuoteBars are also available. To request data as `QuoteBars <https://www.quantconnect.com/docs/algorithm-reference/handling-data#Handling-Data-QuoteBars>`_ you must specify the type in the query.

.. tabs::

   .. code-tab:: c#

        // Get BTCUSD symbol and use it to request history
        var btcusd = AddCrypto("BTCUSD", Resolution.Daily, Market.GDAX).Symbol;
        var quotes = History<QuoteBar>(btcusd, 14, Resolution.Daily)

|

Streaming Warm Up Period
========================

In addition to the methods for manually requesting history above, QuantConnect also supports an automated "fast-forward" system called "Warm Up" which simulates winding back the clock from the time the algorithm is deployed. In a backest, this is the StartDate of your algorithm. Warm Up is a great way to prepare indicators for relatively simple strategies, but if you have a dynamic universe of assets we recommend manually requesting historical data when required.

The Warm Up API supports a set number of bars, or a period based warm-up. Warm Up should be called in your ``Initialize()`` method. An example of using Warm Up can be found `here <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/WarmupAlgorithm.py#L50>`_.

.. tabs::

   .. code-tab:: c#

         // Wind time back 7 days from start:
        SetWarmup(TimeSpan.FromDays(7));

        // Feed in 100 bars before start date:
        SetWarmup(100);

   .. code-tab:: py

         # Wind time back 7 days from start:
        self.SetWarmup(timedelta(7))

        # Feed in 100 bars before start date:
        self.SetWarmup(100)

**Distinguishing Warmup from Reality**

Your algorithm may need to distinguish warm-up data from real data. QuantConnect makes this possible with a boolean flag ``self.IsWarmingUp``. A common application of this flag might look like this:

.. tabs::

   .. code-tab:: c#

        // In Initialize
        var emaFast = EMA("IBM", 50);
        var emaSlow = EMA("IBM", 100);
        SetWarmup(100);

        // In OnData: Don't run if we're warming up our indicators.
        if (IsWarmingUp) return;

   .. code-tab:: py

        # In Initialize
        self.emaFast = self.EMA("IBM", 50);
        self.emaSlow = self.EMA("IBM", 100);
        self.SetWarmup(100);

        // In OnData: Don't run if we're warming up our indicators.
        if self.IsWarmingUp: return

**Warmup Limitations**

Algorithm warm-up is useful if you have a streaming algorithm which can incrementally build algorithm state. However, it has several limitations:

* Trades cannot be performed during warm-up as they would impact the algorithm portfolio and would be trading on fictional fast-forwarded data.

* Due to technical limitations, universe selection cannot be fast-forwarded. Any universe selection is skipped until real-time is reached.