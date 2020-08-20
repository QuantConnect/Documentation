.. _live-trading-overview:

=======================
Live Trading - Overview
=======================

|

Introduction
============

QuantConnect has supported live trading since 2015. We have battle-tested, colocated servers that serve thousands of live trading clients. Algorithms demand fierce stability and uptime, and it is common for our users to achieve 6-months uptime with no interruptions. To achieve this, we purchased modern servers and racked them in Equinix to give the QuantConnect community the best possible stability.

QuantConnect strives to provide a consistent environment between backtesting and live trading with as few differences as possible. In most situations, you will not notice any difference. In the following sections, we'll introduce a few features and helpers for your live trading strategies.

To distinguish between backtesting and live, you can use the ``LiveMode`` boolean property.

.. tabs::

   .. code-tab:: c#

        if (LiveMode) {
              Debug("Trading Live!");
        }

   .. code-tab:: py

        if self.LiveMode:
              self.Debug("Trading Live!")

|

Supported Brokerages
====================

Algorithms designed in QuantConnect can be seamlessly live traded on your brokerage accounts. We send the algorithm signals to your brokerage and track the algorithm state. Algorithms can be deployed immediately at any time of the day or night. A subscription is required for live trading.

.. list-table::
   :widths: 25 50
   :header-rows: 1

   * - Brokerage
     - Description

   * - `Interactive Brokers <https://gdcdyn.interactivebrokers.com/en/index.php?f=4695>`_

       Equity, FOREX, Futures, Options
     - Interactive Brokers (IB) is a low cost provider of trade execution and clearing services for individuals, advisors, prop trading groups, brokers, and hedge funds. IB's premier technology provides direct access to stocks, options, futures, forex, bonds and funds on over 100 markets worldwide from a single IB Universal account. Member NYSE, FINRA, SIPC. `Subscription <https://www.quantconnect.com/upgrade>`_ required for live trading.

   * - `OANDA <https://www.oanda.com/>`_

       FOREX, CFD
     - Through our integration with OANDA Brokerage we can offer FOREX or CFD trading to users worldwide. Accounts can be opened with as little as $1 USD.

   * - `Coinbase Pro <https://pro.coinbase.com/>`_

       Crypto
     - Coinbase Pro is the largest US based cryptocurrency exchange. Owned by Coinbase, users can easily purchase cryptocurrency through Coinbase and transfer it to their Coinbase Pro account. Our Coinbase Pro integration allows users to trade BTC, LTC, and ETH cryptocurrency pairs.

   * - `FXCM <https://www.fxcm.com/uk/>`_

       FOREX, CFD
     - FXCM is a direct market access (DMA) broker offering low spreads and brokerage fees as low as £0.07 per side for popular currencies. FXCM trading is available to users worldwide, and accounts can be opened with as little as 50 GBP

   * - **Paper Trading**

       Equity, FOREX, CFD
     - See how your algorithm would have performed with our paper trading feature. We use real live-data feeds, but a virtual brokerage to execute your trades. Each project is allocated $100,000 virtual currency to track how you've performed.

.. figure:: https://cdn.quantconnect.com/web/i/docs/docs-live-highlights.png

   Live Trading Highlights.

|

Ram Allocations
===============

Live trading servers come in sizes 512Mb, 1GB, and 2GB. These handle 99.9% of user requirements. Users often use 8-32GB of RAM in backtesting, and are concerned their live algorithms will not work. This is because in backtesting the data is being piped into your algorithm at roughly 100,000x speed. Many data objects are cached to achieve these speeds, so you use much more RAM in backtesting.

|

Running Multiple Algorithms
===========================

QuantConnect supports running multiple algorithms at the same time on different brokerage accounts. You can simply select another server and deploy your second algorithm.

Interactive Brokers can easily create new subaccounts and logins. If you'd like to break up your account into multiple smaller accounts, contact Interactive Brokers.

Deploying two algorithms to the same brokerage account is slightly more difficult. Imagine you had two algorithms fighting to set the target portfolio; one trying to buy shares in Apple, and the other trying to sell them. Because of this, we don't allow deploying two projects to the same brokerage account. Instead, you can deploy two algorithms in one project via the Algorithm Framework.

With the ``QCAlgorithmFramework`` you can set two Alpha Models with the ``CompositeAlphaModel``. The CompositeAlphaModel merges the signals of two Alphas. These merged Alpha Model signals are then reconciled in the Portfolio Construction Model, which makes the final decision how much capital to allocate to each signal.

For more information on the CompositeAlphaModel, see the `Algorithm Framework <https://www.quantconnect.com/docs/algorithm-framework/alpha-creation#Alpha-Creation-Multi-Alpha-Algorithms>`_.

