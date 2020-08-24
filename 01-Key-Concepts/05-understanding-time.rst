.. _key-concepts-understanding-time:

==================
Understanding Time
==================

.. raw:: html

    <style>
    .videoWrapper {
      position: relative;
      padding-bottom: 56.25%; /* 16:9 */
      height: 0;
    }
    .videoWrapper iframe {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
    }
    </style>
    <div class="videoWrapper">
    <iframe src="https://player.vimeo.com/video/420736510" width="640" height="360" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
    </div>

|

Introduction
============

The core technology behind QuantConnect algorithmic trading is an event-based, streaming analysis system called `LEAN <https://www.lean.io>`_. LEAN attempts to model the stream of time as accurately as possible, presenting data ("events") to your algorithms in the order it arrives, as you would experience in reality.

All QuantConnect algorithms have this time-stream baked in as the primary event handler: ``OnData(slice)``. The ``Slice`` object represents all of the data at a moment of time, a *"time-slice"*. No matter what data you request, you receive it in the order created according to simulated algorithm time. By only letting your algorithm see the present and past moments, we can prevent the most common quantitative-analysis error, `look-ahead bias <https://www.investopedia.com/terms/l/lookaheadbias.asp>`_.

|

.. _key-concepts-understanding-time-ticks-bars-and-the-time-frontier:

Ticks, Bars and the Time Frontier
=================================

Data comes in two different "shapes" according to the time period it covers: point values, or period values. These have different properties in LEAN which determine when the data is emitted to your algorithm. The coordination of emitting this data is controlled by the *Time Frontier*. In QuantConnect, Ticks are point values, and Bars have a period.

.. figure:: https://cdn.quantconnect.com/docs/i/time-v-endtime_rev0.png

**The Time Frontier**

QuantConnect allows your algorithm to request data for multiple securities and multiple resolutions. This creates a situation where one of your data subscriptions is ready to emit, but another subscription with a longer period may still be constructing its bar. To coordinate this data we use the *End Time* of a data point to transmit it to your algorithm.

.. figure:: https://cdn.quantconnect.com/docs/i/time-frontier_rev1.gif

Once a datapoint's *EndTime* has passed, it will be transmitted to your algorithm OnData() method. For bar data, this is the beginning of the *next* bar. Your algorithm is only permitted to access values of securities from before this Time Frontier, preventing you from accidentally looking into the future.

The ``Time`` property in your algorithm is always equal to this Time Frontier. This is also used as the timestamp for any :ref:`logging and debugging <algorithm-reference-logging-and-debug>` messages.


**Bar Data - Period Values**

Bar data represents the aggregation of a period of data into a single object. In QuantConnect, we make this easy for you by pre-aggregating billions of raw trade-ticks into *Trade Bars*, and quote-ticks into *Quote Bars*.

The close of a bar is not known until the start of the next bar, which can sometimes be confusing. For example, a price bar for Friday will include all the ticks from Friday 00:00 to Friday 23:59.99999, but it will actually be emitted to your algorithm on Saturday at midnight. Because of this, any orders you create after analyzing the Friday data will be sent to your brokerage on Saturday, when most markets are closed. QuantConnect automatically turns your order into a ``MarketOnOpen`` order, which will be filled Monday morning.

.. figure:: https://cdn.quantconnect.com/docs/i/bar-stream_rev0.png

When there are no ticks during a period, the previous bar transmitted is copied and emitted at the request resolution. In QuantConnect, this is the default behavior for bar data and it is referred to as "filling the data forward". You can configure whether this is enabled when you :ref:`request the security data <algorithm-reference-initializing-algorithms-selecting-asset-data>`.

QuantConnect provides data in Second, Minute, Hour, and Daily bar format. To create other periods of bars, you need to :ref:`consolidate <algorithm-reference-consolidating-data>` these short periods into larger ones.

**Tick Data - Point Values**

Tick data represents a single trade or quote made on the market. It is a discrete event that does not have a period of time attached to it. These events are emitted as soon as they arrive to LEAN.

.. figure:: https://cdn.quantconnect.com/docs/i/tick-stream_rev2.png

``Tick`` objects have the same value of Time and EndTime because they have no period. They represent instantaneous point values and cannot be filled forward.

|

Batch vs Stream Analysis
========================

Backtesting platforms come in two general varieties, batch processing or event streaming.

Batch processing backtesting is much simpler. It loads all data into an array and passes it to your algorithm for analysis. Because your algorithm has access to future data points, it is easy to introduce look-ahead bias. Most home-grown analysis tools are batch systems.

QuantConnect/LEAN is a streaming analysis system. In live trading, data points are generated one after another over time. QuantConnect models this in backtesting, streaming data to your algorithm in fast-forward. Because of this, you do not have access to price data beyond the Time Frontier. Although streaming analysis is slightly trickier to understand, it allows your algorithm to seamlessly work in backtests and live trading with no code changes.

|

Algorithm Time Zone
===================

Algorithm time is accessed from the ``Time`` property of QCAlgorithm. Algorithm time defaults to New York timezone for UTC-4 in summer, and UTC-5 in winter. This can be configured by passing an accepted time zone name into the ``SetTimeZone()`` method. A full list of time zone names can be found on `Wikipedia <https://en.wikipedia.org/wiki/List_of_tz_database_time_zones>`_.

.. tabs::

   .. code-tab:: c#

        // In initialize method:
        SetTimeZone("America/New_York");

   .. code-tab:: py

        # In initialize method:
        self.SetTimeZone("America/New_York")

The result of setting a timezone is saved to your ``algorithm.TimeZone`` property. In addition QCAlgorithm maintains UTC time under the ``UtcTime`` property:

.. tabs::

   .. code-tab:: c#

        // UTC Conversion of Algorithm Time
        UtcTime;

   .. code-tab:: py

        # UTC Conversion of Algorithm Time
        self.UtcTime