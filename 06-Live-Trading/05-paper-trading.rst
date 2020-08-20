.. _live-trading-paper-trading:

============================
Live Trading - Paper Trading
============================

|

Introduction
============

QuantConnect supports paper trading your strategies. This is running live, real-time data through your algorithm but executing with fictional capital.

Paper trading is possible using your brokerage (IB, OANDA, FXCM) or the QuantConnect "Paper Brokerage Model". The QuantConnect Paper Model allows you to simulate immediate brokerage fills like backtesting without first signing up to a brokerage account. Interactive Brokers supports paper trading by entering paper trading credentials, while other brokerages require selecting different API endpoints on deploying your algorithm.

*QuantConnect Paper Brokerage Model* deployments are automatically started with $100,000 starting capital, and the project capital is saved between deployments of the same project. To reset your starting capital, select the "Clone Project" button from the project menu and deploy it to live. Currently, there is no way to configure the starting capital for the project. This service is included with a QuantConnect subscription.

|

Assets Supported
================

Whether our paper model or a real brokerage, you can source data for your algorithm from QuantConnect or your brokerage directly. Currently, only Interactive Brokers Brokerage data feed is supported, as the other brokerage data sources are identical to the QuantConnect stream, so we have not built in ways to use them directly.

**QuantConnect Data Feed**

Using the QuantConnect data feed, you can access the asset classes listed below. Paper trading on Futures or Options assets is not supported at this time.

* US Equity Price / Exchange Tick Stream
* FX / OANDA Brokerage Feed
* FX / FXCM Brokerage Feed
* Crypto / Coinbase Pro Brokerage Feed
* Universe Selection

**Interactive Brokers Data Feed**

Using Interactive Brokers as your data source, you can access the asset classes below. You must have the appropriate subscriptions with Interactive Brokers for the assets you need.

* US Equity Price / IB Bars
* US Futures / IB Bars
* US Options / IB Bars
* FX / IB Spread

Universe selection is not supported by the Interactive Brokers Brokerage data feed at this time.