==============================
Key Concepts - Getting Started
==============================

|

Welcome
=======

Welcome to QuantConnect. We are an open-source, community-driven algorithmic trading platform. Our trading engine is powered by LEAN, a cross-platform, multi-asset technology which brings cutting edge finance to the open-source community. We support C#, Python, and F# programming languages, making us a truly open platform.

|

Our Mission
===========

We believe the future of finance is automated, and we plan to be the quantitative trading infrastructure of the future. Algorithmic trading is a powerful tool, and we want to open it up to all investors. For more information on our mission check out our Manifesto.

|

Security Privacy
================

You own all your intellectual property and your code. Your code is private by default unless you explicitly share it with the community in the forums or with another team member via collaboration. You are creating valuable intellectual property, and we respect this and wish to make it easier. We limit the QuantConnect staff members who have access to the database. If we ever need access to your algorithm for debugging, then we will explicitly request permission first. For more information please see our terms and conditions.

|

Data Library
============

We provide an enormous library of data for your backtesting roughly 40TB in size. For more information on the data library and its symbols, see the Data Library. This library includes:

.. list-table::
   :header-rows: 1

   * - Asset Class
     - Source
     - Start Date
     - Symbols
     - Resolution
     - Type
   * - US Equity
     - QuantQuote
     - Jan 1998
     - ≈30,000
     - Tick, Sec, Min, Hour, Daily
     - Trades
   * - Forex
     - FXCM
     - Apr 2007
     - 13
     - Tick, Sec, Min, Hour, Daily
     - Quotes
   * - Forex
     - OANDA
     - Apr 2004
     - 71
     - Tick, Sec, Min, Hour, Daily
     - Quotes
   * - CFD
     - OANDA
     - Apr 2004
     - 50
     - Tick, Sec, Min, Hour, Daily
     - Quotes
   * - Options
     - AlgoSeek
     - Jan 2010
     - ≈4000
     - Minute Bars Only
     - Trades & Quotes
   * - Futures
     - AlgoSeek
     - Jan 2009
     - ≈150
     - Tick, Second, Minute
     - Trades & Quotes
   * - Crypto
     - CoinAPI
     - Jan 2015
     - 12
     - Tick, Second, Minute
     - Trades

|

Business Model
==============

QuantConnect makes backtesting available for free and charges a small monthly fee to cover live algorithm trading. This offsets our costs and aligns our interests with yours - our client. We also work with brokerages to make your live trading free.

In 2018 driven by user demand, we launched Alpha Streams to systematically match good algorithms with hedge funds. Alpha Streams is a world first marketplace of hedge funds who compete to license your strategy. When a fund licenses your algorithm, we pass along 100% of your revenues to you and invoice the fund directly a revenue share equivalent to 30%.