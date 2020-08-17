=====================
Data Library - Equity
=====================

|

Introduction
============

QuantConnect provides survivorship bias-free, split- and dividend-adjusted US Equities data from January 1st, 1998 to today on all US stocks. Over this period, a number of new stocks have entered the market, and others have been delisted. When a company name/symbol changes, the data will automatically be mapped over to the original name. By default data is adjusted for splits and dividends.

All data is available in tick, second, minute, hourly, and daily resolution. Tick data is provided in the order the trades were made, but the data itself is time-stamped to the previous second.

The Tick data is raw and unfiltered - which includes suspicious, untradable, and off-market ticks. This can often result in unrealistic backtests. Before using tick data, you should fully understand all the exchange codes and the "suspicious" flag, which indicates a tick you should ignore. Unless you're an expert, you should use the pre-filtered second and minute data.

+--------------------------------------------------------------------------------------------------------------------------------------------------+
| Data Properties                                                                                                                                  |
+=======================+==========================================================================================================================+
| Resolutions Available | Tick, Second, Minute, Hourly, Daily                                                                                      |
+-----------------------+--------------------------------------------------------------------------------------------------------------------------+
| Data Providers        | `QuantQuote <https://www.quantconnect.com/docs/data-library/equity#Equity-About-the-Provider>`_                          |
+-----------------------+--------------------------------------------------------------------------------------------------------------------------+
| Start Date            | January 1st, 1998.                                                                                                       |
+-----------------------+--------------------------------------------------------------------------------------------------------------------------+
| Symbol Universe       | ≈ 19,700 Tickers ( `Download list <https://quantquote.com/docs/symbol_map_comnam.csv>`_ )                                |
+-----------------------+--------------------------------------------------------------------------------------------------------------------------+

Data must be added in the Initialize() method manually, or selected by universe selection.

.. tabs::

   .. code-tab:: c#

        //Manually adding inside your Initialize() method:
        public override void Initialize() {
            AddEquity("IBM", Resolution.Minute);
        }
        // Accessing requested data
        public override void OnData(Slice data) {
            //via a tradebar dictionary (symbol - bar)
            data.Bars["IBM"].Close
            //Or via a ticks list:
            data.Ticks["IBM"][0].Close
        }

   .. code-tab:: py

        # Manually adding inside your Initialize() method:
        def Initialize(self):
            self.AddEquity("IBM", Resolution.Minute)
        # Accessing requested data
        def OnData(self, data):
            # via a tradebar dictionary (symbol - bar)
            data.Bars["IBM"].Close
            //Or via a ticks list:
            data.Ticks["IBM"][0].Close

Live trading for US Equities is only available on Interactive Brokers. When deploying an algorithm to Interactive Brokers, you also have the option to use them for your data feed. When using Interactive Brokers for your data feed, you must make sure you're subscribed to the asset classes you need.

Live QuantConnect data feed tick data comes directly from the exchanges. Like backtesting tick data, it is also raw and unfiltered.

For more information on using this data in your algorithm, see the Initializing Algorithms.

|

Share Splits
============

QuantConnect provides split and merger data on all US stocks back to January 1st, 1998. When a symbol is requested with prior splits/mergers, we automatically pass the previous symbol data into your algorithm. You can monitor the split/merger events using the built-in event handlers.

.. tabs::

   .. code-tab:: c#

        // Manually adding inside your Initialize() method:
        public override void Initialize() {
            AddEquity("IBM", Resolution.Minute);
        }
        // v2.0 Technique: Dedicated Event handler:
        public void OnData(Splits data) {
            //access Split objects via Splits dictionary
            data["IBM"].SplitFactor // e.g. 1 -> 2 split-> split factor of 2.
        }
        // v3.0 Technique: Accessing via Slice object:
        public override void OnData(Slice data) {
            data.Splits["IBM"].SplitFactor;
        }

   .. code-tab:: py

        # Splits data is accessible via the Splits property in the slice:
        def OnData(self, data):
              # e.g. 1 -> 2 split-> split factor of 2.
              data.Splits["IBM"].SplitFactor

|

Dividends
=========

QuantConnect provides dividend payment data on all US stocks back to January 1st, 1998. Data is passed into your algorithm with dedicated event handlers. The dividend event is triggered on the payment date.

.. tabs::

   .. code-tab:: c#

        // v2.0 Technique: Dedicated Event handler:
        public void OnData(Dividends data) {
            //access Dividend objects via Dividends dictionary
            data["IBM"].Distribution; // Cash dividend
        }
        // v3.0 Technique: Accessing via Slice object:
        public override void OnData(Slice data) {
            data.Dividends["IBM"].Distribution;
        }

   .. code-tab:: py

        # Dividend data is accessible via the Dividends property in the slice:
        def OnData(self, data):
              data.Dividends["IBM"].Distribution # Cash dividend

|

Corporate Fundamentals
======================

QuantConnect provides corporate fundamental data for all US Equities to perform stock selection based on fundamental criteria. See the `Morningstar US Corporate Fundamentals <https://www.quantconnect.com/docs/data-library/fundamentals>`_ data page for more information.

|

Timezone
========

QuantQuote equity data is set in its local time, New York Time. This means that when accessing equity data, all data will be time stamped in New York Time.

|

About the Provider
==================

.. figure:: https://cdn.quantconnect.com/web/i/providers/quantquote.png
   :align: center

`QuantQuote <https://quantquote.com/>`_ is a leading provider of high-resolution historical intraday stock data and live feeds. Their cost-effective and easy to use datasets have given hundreds of customers around the world the competitive edge.

QuantQuote data files are available for all NASDAQ and NYSE listed stocks starting from January 1998 to the present. The dataset is research ready and contains split and dividend adjustments, earnings data, and accounts for corporate events and survivorship bias.

Data is available with a full array of format customization options designed to make the data instantly deployable and compatible with any trading software. For a full list of available data customizations, please visit QuantQuote's `data order page <https://quantquote.com/purchase.php>`_.

A full data description and specification can be found in QuantQuote's `whitepaper <https://quantquote.com/docs/TickView_Historical_Trades.pdf>`_.