===========
Equity Test
===========

|

Introduction
============

QuantQuote equity data is set in its local time, New York Time. This means that when accessing equity data, all data will be time stamped in New York Time.

Live trading for US Equities is only available on Interactive Brokers. When deploying an algorithm to Interactive Brokers, you also have the option to use them for your data feed. When using Interactive Brokers for your data feed, you must make sure you're subscribed to the asset classes you need.

Live QuantConnect data feed tick data comes directly from the exchanges. Like backtesting tick data, it is also raw and unfiltered.

For more information on using this data in your algorithm, see the Initializing Algorithms.

|

Share Splits
============

QuantConnect provides split and merger data on all US stocks back to January 1st, 1998. When a symbol is requested with prior splits/mergers, we automatically pass the previous symbol data into your algorithm. You can monitor the split/merger events using the built-in event handlers.

Corporate Fundamentals
======================

QuantQuote equity data is set in its local time, New York Time. This means that when accessing equity data, all data will be time stamped in New York Time.

|

Timezone
========

QuantQuote equity data is set in its local time, New York Time. This means that when accessing equity data, all data will be time stamped in New York Time.

|

About the Provider
==================

QuantQuote equity data is set in its local time, New York Time. This means that when accessing equity data, all data will be time stamped in New York Time.