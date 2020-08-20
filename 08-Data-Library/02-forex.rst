.. _data-library-forex:

====================
Data Library - Forex
====================

|

Introduction
============

QuantConnect supports Forex trading through two popular brokerages: OANDA and FXCM. As most brokerages offer different asset pricing, we have prepared and hosted separate datasets from both brokerages we support.

In total, QuantConnect hosts 13 currency pairs from Apr 2007 to present provided by FXCM, and 71 currency pairs from Apr 2004 to present provided by OANDA. These currencies are provided as quote data in tick, second, minute, hourly, and daily resolutions.

To subscribe to FOREX data, use the ``AddForex()`` method with the currency pair you'd like information about:

.. tabs::

   .. code-tab:: c#

        // Complete Add Forex API - Including Default Parameters:
        AddForex(string pair,
                 Resolution resolution = Resolution.Minute,
                 string market = null,
                 bool fillDataForward = true,
                 decimal leverage = 0m)

   .. code-tab:: py

        # Complete Add Forex API - Including Default Parameters:
        self.AddForex(string pair,
            Resolution resolution = Resolution.Minute,
            string market = null,
            bool fillDataForward = true,
            decimal leverage = 0m)

When requesting Forex data, you should set your data provider via the ``market`` parameter. Forex has two supported market values: FXCM and Oanda.

.. tabs::

   .. code-tab:: c#

        AddForex("EURUSD", Resolution.Minute, Market.Oanda);
        AddForex("EURUSD", Resolution.Minute, Market.FXCM);

   .. code-tab:: py

        self.AddForex("EURUSD", Resolution.Minute, Market.Oanda)
        self.AddForex("EURUSD", Resolution.Minute, Market.FXCM)

Quotes can be accessed in the Slice object in OnData event handler:

.. tabs::

   .. code-tab:: c#

        var bar = data["EURUSD"];

   .. code-tab:: py

        bar = data["EURUSD"]

|

OANDA Brokerage Forex Data
==========================

QuantConnect provides 71 OANDA Brokerage currency pairs for backtesting and trading.

OANDA data is in GMT timezone. Although many popular charting websites convert this to EST for display, QuantConnect has elected to leave it in GMT and not manipulate the data. When comparing the data to external references, make sure to account for the different time zones.

+---------------------------------------------------------------------------------------------------------------+
| Data Properties                                                                                               |
+=================+=============================================================================================+
| Data Provider	  | `OANDA <https://www.quantconnect.com/docs/data-library/forex#Forex-About-the-Providers>`_   |
+-----------------+---------------------------------------------------------------------------------------------+
| Start Date      | Mixed Dates: Earliest starts May 30th, 2004                                                 |
+-----------------+---------------------------------------------------------------------------------------------+
| Symbol Universe | 71 Currency Pairs ( `More Information <https://www.oanda.com/forex-trading/markets/live>`_) |
+-----------------+---------------------------------------------------------------------------------------------+

+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Contracts Available                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
+============================================================================+===========================================================================+===========================================================================+===========================================================================+===========================================================================+===========================================================================+
| | `AUDCAD <https://www.quantconnect.com/data#symbol/forex/oanda/AUDCAD>`_  | | `AUDCHF <https://www.quantconnect.com/data#symbol/forex/oanda/AUDCHF>`_ | | `AUDHKD <https://www.quantconnect.com/data#symbol/forex/oanda/AUDHKD>`_ | | `AUDJPY <https://www.quantconnect.com/data#symbol/forex/oanda/AUDJPY>`_ | | `AUDNZD <https://www.quantconnect.com/data#symbol/forex/oanda/AUDNZD>`_ | | `AUDSGD <https://www.quantconnect.com/data#symbol/forex/oanda/AUDSGD>`_ |
| | (Oanda: 'AUD_CAD')                                                       | | (Oanda: 'AUD_CHF')                                                      | | (Oanda: 'AUD_HKD')                                                      | | (Oanda: 'AUD_JPY')                                                      | | (Oanda: 'AUD_NZD')                                                      | | (Oanda: 'AUD_SGD')                                                      |
+----------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+
| | `AUDUSD <https://www.quantconnect.com/data#symbol/forex/oanda/AUDUSD>`_  | | `CADCHF <https://www.quantconnect.com/data#symbol/forex/oanda/CADCHF>`_ | | `CADHKD <https://www.quantconnect.com/data#symbol/forex/oanda/CADHKD>`_ | | `CADJPY <https://www.quantconnect.com/data#symbol/forex/oanda/CADJPY>`_ | | `CADSGD <https://www.quantconnect.com/data#symbol/forex/oanda/CADSGD>`_ | | `CHFHKD <https://www.quantconnect.com/data#symbol/forex/oanda/CHFHKD>`_ |
| | (Oanda: 'AUD_USD')                                                       | | (Oanda: 'CAD_CHF')                                                      | | (Oanda: 'CAD_HKD')                                                      | | (Oanda: 'CAD_JPY')                                                      | | (Oanda: 'CAD_SGD')                                                      | | (Oanda: 'CHF_HKD')                                                      |
+----------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+
| | `CHFJPY <https://www.quantconnect.com/data#symbol/forex/oanda/CHFJPY>`_  | | `CHFZAR <https://www.quantconnect.com/data#symbol/forex/oanda/CHFZAR>`_ | | `EURAUD <https://www.quantconnect.com/data#symbol/forex/oanda/EURAUD>`_ | | `EURCAD <https://www.quantconnect.com/data#symbol/forex/oanda/EURCAD>`_ | | `EURCHF <https://www.quantconnect.com/data#symbol/forex/oanda/EURCHF>`_ | | `EURCZK <https://www.quantconnect.com/data#symbol/forex/oanda/EURCZK>`_ |
| | (Oanda: 'CHF_JPY')                                                       | | (Oanda: 'CHF_ZAR')                                                      | | (Oanda: 'EUR_AUD')                                                      | | (Oanda: 'EUR_CAD')                                                      | | (Oanda: 'EUR_CHF')                                                      | | (Oanda: 'EUR_CZK')                                                      |
+----------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+
| | `EURDKK <https://www.quantconnect.com/data#symbol/forex/oanda/EURDKK>`_  | | `EURGBP <https://www.quantconnect.com/data#symbol/forex/oanda/EURGBP>`_ | | `EURHKD <https://www.quantconnect.com/data#symbol/forex/oanda/EURHKD>`_ | | `EURHUF <https://www.quantconnect.com/data#symbol/forex/oanda/EURHUF>`_ | | `EURJPY <https://www.quantconnect.com/data#symbol/forex/oanda/EURJPY>`_ | | `EURNOK <https://www.quantconnect.com/data#symbol/forex/oanda/EURNOK>`_ |
| | (Oanda: 'EUR_DKK')                                                       | | (Oanda: 'EUR_GBP')                                                      | | (Oanda: 'EUR_HKD')                                                      | | (Oanda: 'EUR_HUF')                                                      | | (Oanda: 'EUR_JPY')                                                      | | (Oanda: 'EUR_NOK')                                                      |
+----------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+
| | `EURNZD <https://www.quantconnect.com/data#symbol/forex/oanda/EURNZD>`_  | | `EURPLN <https://www.quantconnect.com/data#symbol/forex/oanda/EURPLN>`_ | | `EURSEK <https://www.quantconnect.com/data#symbol/forex/oanda/AUDHKD>`_ | | `EURSGD <https://www.quantconnect.com/data#symbol/forex/oanda/EURSGD>`_ | | `EURTRY <https://www.quantconnect.com/data#symbol/forex/oanda/EURTRY>`_ | | `EURUSD <https://www.quantconnect.com/data#symbol/forex/oanda/EURUSD>`_ |
| | (Oanda: 'EUR_NZD')                                                       | | (Oanda: 'EUR_PLN')                                                      | | (Oanda: 'AUD_HKD')                                                      | | (Oanda: 'EUR_SGD')                                                      | | (Oanda: 'EUR_TRY')                                                      | | (Oanda: 'EUR_USD')                                                      |
+----------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+
| | `EURZAR <https://www.quantconnect.com/data#symbol/forex/oanda/EURZAR>`_  | | `GBPAUD <https://www.quantconnect.com/data#symbol/forex/oanda/GBPAUD>`_ | | `GBPCAD <https://www.quantconnect.com/data#symbol/forex/oanda/GBPCAD>`_ | | `GBPCHF <https://www.quantconnect.com/data#symbol/forex/oanda/GBPCHF>`_ | | `GBPHKD <https://www.quantconnect.com/data#symbol/forex/oanda/GBPHKD>`_ | | `GBPJPY <https://www.quantconnect.com/data#symbol/forex/oanda/GBPJPY>`_ |
| | (Oanda: 'EUR_ZAR')                                                       | | (Oanda: 'GBP_AUD')                                                      | | (Oanda: 'GBP_CAD')                                                      | | (Oanda: 'GBP_CHF')                                                      | | (Oanda: 'GBP_HKD')                                                      | | (Oanda: 'GBP_JPY')                                                      |
+----------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+
| | `GBPNZD <https://www.quantconnect.com/data#symbol/forex/oanda/GBPNZD>`_  | | `GBPPLN <https://www.quantconnect.com/data#symbol/forex/oanda/GBPPLN>`_ | | `GBPSGD <https://www.quantconnect.com/data#symbol/forex/oanda/GBPSGD>`_ | | `GBPUSD <https://www.quantconnect.com/data#symbol/forex/oanda/GBPUSD>`_ | | `GBPZAR <https://www.quantconnect.com/data#symbol/forex/oanda/GBPZAR>`_ | | `HKDJPY <https://www.quantconnect.com/data#symbol/forex/oanda/HKDJPY>`_ |
| | (Oanda: 'GBP_NZD')                                                       | | (Oanda: 'GBP_PLN')                                                      | | (Oanda: 'GBP_SGD')                                                      | | (Oanda: 'GBP_USD')                                                      | | (Oanda: 'GBP_ZAR')                                                      | | (Oanda: 'HKD_JPY')                                                      |
+----------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+
| | `NZDCAD <https://www.quantconnect.com/data#symbol/forex/oanda/NZDCAD>`_  | | `NZDCHF <https://www.quantconnect.com/data#symbol/forex/oanda/NZDCHF>`_ | | `NZDHKD <https://www.quantconnect.com/data#symbol/forex/oanda/NZDHKD>`_ | | `NZDJPY <https://www.quantconnect.com/data#symbol/forex/oanda/NZDJPY>`_ | | `NZDSGD <https://www.quantconnect.com/data#symbol/forex/oanda/NZDSGD>`_ | | `NZDUSD <https://www.quantconnect.com/data#symbol/forex/oanda/NZDUSD>`_ |
| | (Oanda: 'NZD_CAD')                                                       | | (Oanda: 'NZD_CHF')                                                      | | (Oanda: 'NZD_HKD')                                                      | | (Oanda: 'NZD_JPY')                                                      | | (Oanda: 'NZD_SGD')                                                      | | (Oanda: 'NZD_USD')                                                      |
+----------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+
| | `SGDCHF <https://www.quantconnect.com/data#symbol/forex/oanda/SGDCHF>`_  | | `SGDHKD <https://www.quantconnect.com/data#symbol/forex/oanda/SGDHKD>`_ | | `SGDJPY <https://www.quantconnect.com/data#symbol/forex/oanda/SGDJPY>`_ | | `TRYJPY <https://www.quantconnect.com/data#symbol/forex/oanda/TRYJPY>`_ | | `USDCAD <https://www.quantconnect.com/data#symbol/forex/oanda/USDCAD>`_ | | `USDCHF <https://www.quantconnect.com/data#symbol/forex/oanda/USDCHF>`_ |
| | (Oanda: 'SGD_CHF')                                                       | | (Oanda: 'SGD_HKD')                                                      | | (Oanda: 'SGD_JPY')                                                      | | (Oanda: 'TRY_JPY')                                                      | | (Oanda: 'USD_CAD')                                                      | | (Oanda: 'USD_CHF')                                                      |
+----------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+
| | `USDCNH <https://www.quantconnect.com/data#symbol/forex/oanda/USDCNH>`_  | | `USDCZK <https://www.quantconnect.com/data#symbol/forex/oanda/USDCZK>`_ | | `USDDKK <https://www.quantconnect.com/data#symbol/forex/oanda/USDDKK>`_ | | `USDHKD <https://www.quantconnect.com/data#symbol/forex/oanda/USDHKD>`_ | | `USDHUF <https://www.quantconnect.com/data#symbol/forex/oanda/USDHUF>`_ | | `USDINR <https://www.quantconnect.com/data#symbol/forex/oanda/USDINR>`_ |
| | (Oanda: 'USD_CNH')                                                       | | (Oanda: 'USD_CZK')                                                      | | (Oanda: 'USD_DKK')                                                      | | (Oanda: 'USD_HKD')                                                      | | (Oanda: 'USD_HUF')                                                      | | (Oanda: 'USD_INR')                                                      |
+----------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+
| | `USDJPY <https://www.quantconnect.com/data#symbol/forex/oanda/USDJPY>`_  | | `USDMXN <https://www.quantconnect.com/data#symbol/forex/oanda/USDMXN>`_ | | `USDNOK <https://www.quantconnect.com/data#symbol/forex/oanda/USDNOK>`_ | | `USDPLN <https://www.quantconnect.com/data#symbol/forex/oanda/USDPLN>`_ | | `USDSAR <https://www.quantconnect.com/data#symbol/forex/oanda/USDSAR>`_ | | `USDSEK <https://www.quantconnect.com/data#symbol/forex/oanda/USDSEK>`_ |
| | (Oanda: 'USD_JPY')                                                       | | (Oanda: 'USD_MXN')                                                      | | (Oanda: 'USD_NOK')                                                      | | (Oanda: 'USD_PLN')                                                      | | (Oanda: 'USD_SAR')                                                      | | (Oanda: 'USD_SEK')                                                      |
+----------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+
| | `USDSGD <https://www.quantconnect.com/data#symbol/forex/oanda/USDSGD>`_  | | `USDTHB <https://www.quantconnect.com/data#symbol/forex/oanda/USDTHB>`_ | | `USDTRY <https://www.quantconnect.com/data#symbol/forex/oanda/USDTRY>`_ | | `USDZAR <https://www.quantconnect.com/data#symbol/forex/oanda/USDZAR>`_ | | `ZARJPY <https://www.quantconnect.com/data#symbol/forex/oanda/ZARJPY>`_ |                                                                           |
| | (Oanda: 'USD_SGD')                                                       | | (Oanda: 'USD_THB')                                                      | | (Oanda: 'USD_TRY')                                                      | | (Oanda: 'USD_ZAR')                                                      | | (Oanda: 'ZAR_JPY')                                                      |                                                                           |
+----------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+---------------------------------------------------------------------------+

To use OANDA Brokerage FX products in QuantConnect, you must specify the QuantConnect symbol, not the OANDA one. Data is automatically passed into your algorithm on request. Requested data must be added in the ``Initialize()`` method.

.. tabs::

   .. code-tab:: c#

        // Access data via dedicated event handlers:
        public void OnData(TradeBars data) {
            data["EURUSD"].Close;
        }
        // Access data via grouped time slice method handlers:
        public override void OnData(Slice data) {
            data.Bars["EURUSD"].Close;
        }

   .. code-tab:: py

        # Access tradebar(midpoints) or quote data (real) via grouped time slice method handlers:
        def OnData(self, data):
            data.Bars["EURUSD"].Close
            data.QuoteBars["EURUSD"].Close

|

FXCM Brokerage Forex Data
=========================

QuantConnect provides 39 currency pairs from FXCM for backtesting and live trading starting as early as April 2007. FXCM currencies have a lower spread than traditional market-makers, as FXCM fills trades directly from a number of liquidity providers and offers low competitive spreads. FXCM charges a fixed per-lot transaction fee rather than a charging spread.

+-------------------------------------------------------------------------------------------------------------+
| Data Properties                                                                                             |
+=====================+=======================================================================================+
| **Data Provider**   | FXCM                                                                                  |
+---------------------+---------------------------------------------------------------------------------------+
| **Start Date**      | Mixed; Major symbols start April 1st, 2007.                                           |
+---------------------+---------------------------------------------------------------------------------------+
| **Symbol Universe** | 39 Currency Pairs Tickers ( `See More <https://www.fxcm.com/forex/currency-pairs/>`_) |
+---------------------+---------------------------------------------------------------------------------------+

+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Pairs Available                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
+==========================================================================+==========================================================================+==========================================================================+==========================================================================+==========================================================================+==========================================================================+
| | `AUDCAD <https://www.quantconnect.com/data#symbol/forex/fxcm/AUDCAD>`_ | | `AUDCHF <https://www.quantconnect.com/data#symbol/forex/fxcm/AUDCHF>`_ | | `AUDJPY <https://www.quantconnect.com/data#symbol/forex/fxcm/AUDJPY>`_ | | `AUDNZD <https://www.quantconnect.com/data#symbol/forex/fxcm/AUDNZD>`_ | | `AUDUSD <https://www.quantconnect.com/data#symbol/forex/fxcm/AUDUSD>`_ | | `CADCHF <https://www.quantconnect.com/data#symbol/forex/fxcm/CADCHF>`_ |
| | (FXCM: 'AUD/CAD')                                                      | | (FXCM: 'AUD/CHF')                                                      | | (FXCM: 'AUD/JPY')                                                      | | (FXCM: 'AUD/NZD')                                                      | | (FXCM: 'AUD/USD')                                                      | | (FXCM: 'CAD/CHF')                                                      |
+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+
| | `CADJPY <https://www.quantconnect.com/data#symbol/forex/fxcm/CADJPY>`_ | | `CHFJPY <https://www.quantconnect.com/data#symbol/forex/fxcm/CHFJPY>`_ | | `EURAUD <https://www.quantconnect.com/data#symbol/forex/fxcm/EURAUD>`_ | | `EURCAD <https://www.quantconnect.com/data#symbol/forex/fxcm/EURCAD>`_ | | `EURCHF <https://www.quantconnect.com/data#symbol/forex/fxcm/EURCHF>`_ | | `EURGBP <https://www.quantconnect.com/data#symbol/forex/fxcm/EURGBP>`_ |
| | (FXCM: 'CAD/JPY')                                                      | | (FXCM: 'CHF/JPY')                                                      | | (FXCM: 'EUR/AUD')                                                      | | (FXCM: 'EUR/CAD')                                                      | | (FXCM: 'EUR/CHF')                                                      | | (FXCM: 'EUR/GBP')                                                      |
+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+
| | `EURJPY <https://www.quantconnect.com/data#symbol/forex/fxcm/EURJPY>`_ | | `EURNOK <https://www.quantconnect.com/data#symbol/forex/fxcm/EURNOK>`_ | | `EURNZD <https://www.quantconnect.com/data#symbol/forex/fxcm/EURNZD>`_ | | `EURSEK <https://www.quantconnect.com/data#symbol/forex/fxcm/EURSEK>`_ | | `EURTRY <https://www.quantconnect.com/data#symbol/forex/fxcm/EURTRY>`_ | | `EURUSD <https://www.quantconnect.com/data#symbol/forex/fxcm/EURUSD>`_ |
| | (FXCM: 'EUR/JPY')                                                      | | (FXCM: 'EUR/NOK')                                                      | | (FXCM: 'EUR/NZD')                                                      | | (FXCM: 'EUR/SEK')                                                      | | (FXCM: 'EUR/TRY')                                                      | | (FXCM: 'EUR/USD')                                                      |
+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+
| | `GBPAUD <https://www.quantconnect.com/data#symbol/forex/fxcm/GBPAUD>`_ | | `GBPCAD <https://www.quantconnect.com/data#symbol/forex/fxcm/GBPCAD>`_ | | `GBPCHF <https://www.quantconnect.com/data#symbol/forex/fxcm/GBPCHF>`_ | | `GBPJPY <https://www.quantconnect.com/data#symbol/forex/fxcm/GBPJPY>`_ | | `GBPNZD <https://www.quantconnect.com/data#symbol/forex/fxcm/GBPNZD>`_ | | `GBPUSD <https://www.quantconnect.com/data#symbol/forex/fxcm/GBPUSD>`_ |
| | (FXCM: 'GBP/AUD')                                                      | | (FXCM: 'GBP/CAD')                                                      | | (FXCM: 'GBP/CHF')                                                      | | (FXCM: 'GBP/JPY')                                                      | | (FXCM: 'GBP/NZD')                                                      | | (FXCM: 'GBP/USD')                                                      |
+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+
| | `NZDCAD <https://www.quantconnect.com/data#symbol/forex/fxcm/NZDCAD>`_ | | `NZDCHF <https://www.quantconnect.com/data#symbol/forex/fxcm/NZDCHF>`_ | | `NZDJPY <https://www.quantconnect.com/data#symbol/forex/fxcm/NZDJPY>`_ | | `NZDUSD <https://www.quantconnect.com/data#symbol/forex/fxcm/NZDUSD>`_ | | `TRYJPY <https://www.quantconnect.com/data#symbol/forex/fxcm/TRYJPY>`_ | | `USDMXN <https://www.quantconnect.com/data#symbol/forex/fxcm/USDMXN>`_ |
| | (FXCM: 'NZD/CAD')                                                      | | (FXCM: 'NZD/CHF')                                                      | | (FXCM: 'NZD/JPY')                                                      | | (FXCM: 'NZD/USD')                                                      | | (FXCM: 'TRY/JPY')                                                      | | (FXCM: 'USD/MXN')                                                      |
+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+
| | `USDCAD <https://www.quantconnect.com/data#symbol/forex/fxcm/USDCAD>`_ | | `USDCHF <https://www.quantconnect.com/data#symbol/forex/fxcm/USDCHF>`_ | | `USDCNY <https://www.quantconnect.com/data#symbol/forex/fxcm/USDCNY>`_ | | `USDHKD <https://www.quantconnect.com/data#symbol/forex/fxcm/USDHKD>`_ | | `USDJPY <https://www.quantconnect.com/data#symbol/forex/fxcm/USDJPY>`_ | | `USDNOK <https://www.quantconnect.com/data#symbol/forex/fxcm/USDNOK>`_ |
| | (FXCM: 'USD/CAD')                                                      | | (FXCM: 'USD/CHF')                                                      | | (FXCM: 'USD/CNY')                                                      | | (FXCM: 'USD/HKD')                                                      | | (FXCM: 'USD/JPY')                                                      | | (FXCM: 'USD/NOK')                                                      |
+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+
| | `USDSEK <https://www.quantconnect.com/data#symbol/forex/fxcm/USDSEK>`_ | | `USDTRY <https://www.quantconnect.com/data#symbol/forex/fxcm/USDTRY>`_ | | `USDZAR <https://www.quantconnect.com/data#symbol/forex/fxcm/USDZAR>`_ | | `ZARJPY <https://www.quantconnect.com/data#symbol/forex/fxcm/ZARJPY>`_ |                                                                          |                                                                          |
| | (FXCM: 'USD/SEK')                                                      | | (FXCM: 'USD/TRY')                                                      | | (FXCM: 'USD/ZAR')                                                      | | (FXCM: 'ZAR/JPY')                                                      |                                                                          |                                                                          |
+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+--------------------------------------------------------------------------+

To use FXCM currency pairs in QuantConnect, you must specify the QuantConnect symbol, not the FXCM one. Data is automatically passed into your algorithm on request. Requested data must be added in the ``Initialize()`` method.

.. tabs::

   .. code-tab:: c#

        // Manual add symbols required in your initialize method:
        public override void Initialize() {
            AddForex("EURUSD", Resolution.Minute);
        }
        // v2.0 Technique: Access data via dedicated event handlers:
        public void OnData(TradeBars data) {
            data["EURUSD"].Close;
        }
        // v3.0 Technique: Access data via grouped time slice method handlers:
        public override void OnData(Slice data) {
            data.Bars["EURUSD"].Close;
        }

   .. code-tab:: py

        # Manual add symbols required in your initialize method:
        def Initialize(self):
            self.AddForex("EURUSD", Resolution.Minute, Market.FXCM)

        # Access data via grouped time slice method handlers:
        def OnData(self, data):
            data.Bars["EURUSD"].Close

|

Timezone
========

OANDA data is in UTC timezone. Although many popular charting websites convert this to EST for display, QuantConnect has elected to leave it in UTC and not manipulate the data. When comparing the data to external references, make sure to account for the different time zones. While Oanda data is in UTC Time, the exchange is set to its local time, New York Time. So data accessed from this brokerage is timestamped in New York Time. Meanwhile, all FXCM data is set in UTC-05 Time, or Eastern Standard Time (EST).

|

About the Providers
===================

.. figure:: https://cdn.quantconnect.com/web/i/providers/oanda.png
   :align: center
   :width: 200

`OANDA <https://www.oanda.com/>`_ uses innovative computer and financial technology to provide Internet-based forex trading and currency information services to everyone, from individuals to large corporations, from portfolio managers to financial institutions. OANDA is a market maker and a trusted source for currency data. It has access to one of the world's largest historical, high frequency, filtered currency databases.

.. figure:: https://cdn.quantconnect.com/web/i/providers/fxcm.png
   :align: center
   :width: 200

The `FXCM <https://www.fxcm.com/>`_ group of companies (collectively, the "FXCM Group") is a leading international provider of online foreign exchange (forex) trading, CFD trading, spread betting and related services to retail and institutional customers worldwide. Founded in 1999 and headquartered in New York, NY, FXCM has operating subsidiaries regulated in a number of jurisdictions, including the United Kingdom and Australia. We also maintain offices in Italy, France, Germany, and Greece.

At the heart of FXCM's client offering is No Dealing Desk forex trading. Clients benefit from FXCM's large network of forex liquidity providers enabling FXCM to offer competitive spreads on major currency pairs. Clients have the advantage of mobile trading, one-click order execution and trading from real-time charts. FXCM's U.K. subsidiary, Forex Capital Markets Limited, also offers CFD products with no re-quote trading and allows clients to trade oil, gold, silver and stock indices along with forex on one platform. In addition, FXCM offers educational courses on forex trading and provides access to exclusive tools through FXCM PLUS.

While FXCM has made every effort to ensure the accuracy of the information provided to QuantConnect, FXCM does not guarantee its accuracy, and will not accept liability for any loss or damage that may arise directly or indirectly from the content or your inability to access the website, for any delay in or failure of the transmission or the receipt of any instruction or notifications sent through this website. Nothing on this website shall be considered a solicitation to buy or an offer to sell any product or service to any person in any jurisdiction where such offer, solicitation, purchase or sale would be unlawful under the laws or regulations of such jurisdiction.

Trading forex/CFDs on margin carries a high level of risk and may not be suitable for all investors as you could sustain losses in excess of deposits. Leverage can work against you. Be aware and fully understand all risks associated with the market and trading. Before deciding to trade any products, carefully consider your financial situation and experience level. Seek advice from an independent financial advisor.