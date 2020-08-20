====
CBOE
====

|

.. list-table:: Demonstration Algorithms
   :header-rows: 1

   * - C#
     - Py

   * - `CachedAlternativeDataAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/AltData/CachedAlternativeDataAlgorithm.cs>`_
     - `CachedAlternativeDataAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/AltData/CachedAlternativeDataAlgorithm.py>`_

|

Introduction
============

CBOE publishes daily exports of their most popular indexes which QuantConnect caches for easy deployment. The indexes are centered on their volatility products, and start in 2004.

The VIX index is an up to the minute estimate of how the market expects the volatility to be over the following 30 days, calculated based on the bid-ask spread of SPX options.

|

Usage
=====

**Requesting Data**

To add CBOE data to your algorithm use the ``AddData()`` system to request the data. As with all datasets, you should save a reference to your symbol for easy use later in your algorithm. For detailed documentation on using custom data see `Importing Custom Data <https://www.quantconnect.com/docs/algorithm-reference/importing-custom-data>`_.

.. tabs::

   .. code-tab:: c#

        // Daily OHLC for VIX Index since 2004.
        var vix = AddData<CBOE>("VIX").Symbol;

   .. code-tab:: py

        # Daily OHLC for VIX Index since 2004.
        vix = self.AddData(CBOE, "VIX").Symbol

**Accessing Data**

Data can be accessed via the Slice events. Slice delivers the unique events to your algorithm as they happen. We recommend saving the symbol object when you add the data for easy access to slice later. Data is available in daily resolution. You can see an example of the slice accessor in the code below.

.. tabs::

   .. code-tab:: c#

        using QuantConnect.Data.Custom.CBOE;
        namespace QuantConnect.Algorithm.CSharp {
            public class CBOEAlgorithm : QCAlgorithm {
                public override void Initialize() {
                    SetStartDate(2019, 1, 1);

                    // Daily OHLC for VIX Index since 2004.
                    AddData<CBOE>("VIX");
                }

                public override void OnData(Slice data) {
                    // Accessing via Slice event
                    var vix = data.Get<CBOE>();

                    foreach (var volatility in vix.Values)
                    {
                        Log($"Value: {volatility.Value}");
                    }
                }
            }
        }

   .. code-tab:: py

        from QuantConnect.Data.Custom.CBOE import *

            class CBOEAlgorithm(QCAlgorithm):
                def Initialize(self):
                    self.SetStartDate(2019, 1, 1)

                    # Daily OHLC for VIX Index since 2004.
                    self.AddData(CBOE, "VIX")

                def OnData(self, data):
                    # Accessing via Slice event
                    vix = data.Get(CBOE)

                    for volatility in vix.Values:
                        self.Log(f"Value: {volatility.Value}")

All custom data has the properties ``Time``, ``Symbol``, and ``Value``.

|

Historical Data
===============

You can request historical custom data in your algorithm using the custom data Symbol object. To learn more about historical data requests, please visit the `Historical Data <https://www.quantconnect.com/docs/algorithm-reference/historical-data>`_ documentation. If there is no custom data in the period you request, the history result will be empty. The following example gets daily ohlc for vix index since 2004. historical data using the History API.

.. tabs::

   .. code-tab:: c#

        // Request 60 days of daily ohlc for vix index since 2004. history with the vix Symbol
        var history = History<CBOE>(vix, 60, Resolution.Daily);23

   .. code-tab:: py

        # Request 60 days of daily ohlc for vix index since 2004. history with the vix Symbol
        history = self.History(CBOE, vix, 60, Resolution.Daily)

|

Demonstration
=============

(`Python equivalent <https://www.quantconnect.com/terminal/processCache?request=embedded_backtest_293504715e50d71eb4d262fd01c84a1b.html>`_)

.. raw:: html

   <iframe style="border: solid 1px #ebecee; width: 100%; height: 330px" src="https://www.quantconnect.com/terminal/processCache?request=embedded_backtest_fc30babee2d6f886ec89a879b6c5db7b.html"></iframe>

|

Data Properties
===============

.. list-table::

   * - **Volume** (decimal)
     - Volume:

   * - **Open** (decimal)
     - Opening price of the bar: Defined as the price at the start of the time period.

   * - **High** (decimal)
     - High price of the TradeBar during the time period.

   * - **Low** (decimal)
     - Low price of the TradeBar during the time period.

   * - **Close** (decimal)
     - Closing price of the TradeBar. Defined as the price at Start Time + TimeSpan.

   * - **EndTime** (DateTime)
     - The closing time of this bar, computed via the Time and Period

   * - **Period** (TimeSpan)
     - The period of this trade bar, (second, minute, daily, ect...)

|

Personal Trading
================

QuantConnect provides this data set for personal use. Nothing special is needed for personal live trading.

|

Pricing
=======

.. list-table::
   :header-rows: 1

   * - Application Context
     - Subscription Fee

   * - All Applications
     - Free

|

About the Provider
==================

In 1993, Cboe Global Markets introduced the Cboe Volatility Index® (VIX® Index), which was originally designed to measure the market's expectation of 30-day volatility implied by at-the-money S&P 100® Index (OEX® Index) option prices. The VIX Index soon became the premier benchmark for U.S. stock market volatility. It has been regularly featured in the Wall Street Journal, Barron's and other leading financial publications, as well as on business news shows, where the VIX Index is often referred to as the "Fear Index."

.. figure:: https://cdn.quantconnect.com/docs/i/cboe_logo_rev0.png