.. _key-concepts-security-identifiers:

===================================
Key Concepts - Security Identifiers
===================================

|

Introduction
============

Symbols are a way to identify an asset uniquely. They are objects which contain all the information required to identify a security without needing external references or proprietary database look-ups. Symbols were implemented in the LEAN open-source project as a way to identify or "finger-print" tradable assets so that no further database look-up is required.

All QuantConnect and LEAN Algorithm API methods use Symbols to identify the asset for trading.

|

What Is A Symbol
================

Symbols have several public properties you can use to identify the asset uniquely. When serialized together, this class allows the unique identification of millions of different security objects all within a self-contained reference.

.. figure:: https://cdn.quantconnect.com/alpha/docs/i/what-is-symbol_rev0.png

.. code-block::

    Symbol.Market            # Market USA, FXCM, GDAX, Bitfinex, Oanda. Liquidity venues.
    Symbol.SecurityType      # Security Types include Equity, Option, Future, Forex, Crypto and Cfd.
    Symbol.OptionType        # American or European Option.
    Symbol.OptionRight       # OptionRight indicates if a Put or Call.
    Symbol.Date              # Earliest listing date if equities, expiry for future/option.
    Symbol.HasUnderlying     # Is a derivative asset with another underlying asset.

All of this data is encoded into the symbol object. QuantConnect does our best to hide the details of this from your algorithm, but occasionally you'll see it come through as an encoded hash like this: AAPL R735QTJ8XC9X. The first half of the encoded string represents the first ticker AAPL was listed under, the other letters at the end of the string represent other information for the asset (Security Type, Date Listed, Expiry Date, Strike Price, and Listed Market).

.. figure:: https://cdn.quantconnect.com/docs/i/symbol-encoding-examples_rev0.png

**Important: Adding Securities**

When you manually request data with AddSecurity()/AddEquity() methods, QuantConnect assumes you are adding the ticker as of *"today"* and automatically looks up the first ticker that an asset was listed with. Using the Google example above:

.. tabs::

   .. code-tab:: c#

        var goog = AddEquity("GOOG").Symbol;
        Debug(goog.ID.ToString()); // Prints "GOOCV VP83T1ZUHROL"
        Debug(goog);               // Prints Your Reference "GOOG"

   .. code-tab:: py

        self.goog = self.AddEquity("GOOG").Symbol
        self.Debug(self.goog.ID) # Prints "GOOCV VP83T1ZUHROL"
        self.Debug(self.goog)    # Prints Your Reference "GOOG"

To access your reference value for a Symbol, you can use the Symbol.Value property which returns the string ticker you used to add the data to your algorithm (i.e. "GOOG" in our example).

|

Symbols vs Tickers
==================

*Tickers* are a string shortcode representation for an asset. Some examples of popular tickers include "AAPL" for Apple Corporation or "IBM" for International Business Machines Corporation. These *tickers* often change when the company rebrands, or if they merge with another security.

The *ticker* for an asset is not the same as the Symbol. Symbol objects are permanent and track the underlying *entity*. When a company rebrands or changes its name, the QuantConnect Symbol object remains constant, giving algorithms a way to track assets over time reliably.

Tickers are also often reused by different brokerages. For example Coinbase, a leading US Crypto Brokerage lists "BTCUSD" ticker for trading. Its competitor Bitfinex also lists "BTCUSD," and both are tradable in QuantConnect. Symbols allow us to identify which market-maker you are referencing.

|

Symbol Cache
============

To make using the API easier, QuantConnect has built technology called the Symbol Cache which accepts strings and tries to guess which Symbol object you might mean. Because of this, many methods can accept a string such as "IBM" instead of a complete symbol object. We highly recommend you don't rely on this technology and instead save explicit references to your securities when needed.

.. tabs::

   .. code-tab:: c#

        // Example 1: Relying On Symbol Cache:
        AddEquity("IBM");         // Add by IBM string ticker, save reference to Symbol Cache.
        MarketOrder("IBM", 100);  // Determine refering to IBM Equity from Symbol Cache.
        History("AAPL", 14);      // Guess referring to AAPL Equity.

        // Example 2: Correctly Using Symbols:
        var ibm = AddEquity("IBM").Symbol;   // Add IBM Equity string ticker, save Symbol.
        MarketOrder(ibm, 100);               // Use IBM Symbol in future API calls.

        var aapl = Symbol.Create("AAPL", SecurityType.Equity, Market.USA)
        History(aapl, 14)

   .. code-tab:: py

        # Example 1: Relying On Symbol Cache:
        self.AddEquity("IBM")         # Add by IBM string ticker, save reference to Symbol Cache.
        self.MarketOrder("IBM", 100)  # Determine refering to IBM Equity from Symbol Cache.
        self.History("AAPL", 14)      # Makes a guess referring to AAPL Equity.

        # Example 2: Correctly Using Symbols:
        self.ibm = self.AddEquity("IBM").Symbol   # Add IBM Equity string ticker, save Symbol.
        self.MarketOrder(self.ibm, 100)           # Use IBM Symbol in future API calls.

        self.aapl = Symbol.Create("AAPL", SecurityType.Equity, Market.USA)
        self.History(self.aapl, 14)

|

Decoding Symbols
================

When a Symbol is serialized to a string, it will look something like this: SPY R735QTJ8XC9X. This two-part string is a base64 encoded set of data. Encoding all of the properties into a short format allows dense communication without requiring a third party list or look-up.

Most of the time, you will not need to work with these encoded strings. However, QuantConnect provides a method for deserializing Symbol objects into easily consumable objects for use by the API. You can use this method as demonstrated below:

.. tabs::

   .. code-tab:: c#

        var google = Symbol("GOOCV VP83T1ZUHROL");
        google.ID.Market                    # Market.USA
        google.SecurityType                 # SecurityType.Equity
        google.Value                       # GOOCV

   .. code-tab:: py

        google = self.Symbol("GOOCV VP83T1ZUHROL")
        print(google.ID.Market)                             # USA
        print(google.SecurityType)                          # Equity
        print(google.Value)                                # GOOCV

The Market property is used to distinguish between tickers with the same string value representing different underlying assets. A prime example of this is the various market makers who have different prices for EURUSD. QuantConnect stores this data separately, and as they have different fill prices, we treat the execution venues as different *markets*.

|

Symbol Limitations
==================

The downside of Symbols is that it requires knowledge of the initial listing ticker string. In the example above, the initial ticker GOOCV is eventually renamed/mapped into the GOOG class C-shares. QuantConnect is working to also support other identification methods (CUSIP / ISIN / Open-FIGI), but for now, Symbols allow unique identification and free distribution of the LEAN project without references to external data.