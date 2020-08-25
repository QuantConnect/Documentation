.. _data-library-cfd:

===
CFD
===

|

Introduction
============

QuantConnect serves 51 CFD contracts from OANDA, starting on various dates from April 2004. CFD live-trading is only available for non-US residents. Quotes data with tick, second, minute, hour, and daily resolutions are available.

|

OANDA Brokerage CFD Data
========================

QuantConnect provides all OANDA Brokerage CFD contracts for backtesting and trading.

+-----------------------------------------------------------------------------------------------------------------+
| Data Properties                                                                                                 |
+=====================+===========================================================================================+
| **Data Provider**   | `OANDA <https://www.quantconnect.com/data/provider/oanda>`_                               |
+---------------------+-------------------------------------------------------------------------------------------+
| **Start Date**      | Mixed Dates: Earliest starts May 30th, 2004                                               |
+---------------------+-------------------------------------------------------------------------------------------+
| **Symbol Universe** | 51 CFD Contracts (`More Information <https://www.oanda.com/forex-trading/markets/live>`_) |
+---------------------+-------------------------------------------------------------------------------------------+

+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Contracts Available                                                                                                                                                                                                                                                                                                                                                                                  |
+==============================================================================+=============================================================================+=============================================================================+=============================================================================+=============================================================================+
| `AU200AUD <https://www.quantconnect.com/data#symbol/cfd/oanda/AU200AUD>`_    | `BCOUSD <https://www.quantconnect.com/data#symbol/cfd/oanda/BCOUSD>`_       | `CORNUSD <https://www.quantconnect.com/data#symbol/cfd/oanda/CORNUSD>`_     | `DE30EUR <https://www.quantconnect.com/data#symbol/cfd/oanda/DE30EUR>`_     | `EU50EUR <https://www.quantconnect.com/data#symbol/cfd/oanda/EU50EUR>`_     |
+------------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+
| `DE10YBEUR <https://www.quantconnect.com/data#symbol/cfd/oanda/DE10YBEUR>`_  | `CH20CHF <https://www.quantconnect.com/data#symbol/cfd/oanda/CH20CHF>`_     | `FR40EUR <https://www.quantconnect.com/data#symbol/cfd/oanda/FR40EUR>`_     | `HK33HKD <https://www.quantconnect.com/data#symbol/cfd/oanda/HK33HKD>`_     | `JP225USD <https://www.quantconnect.com/data#symbol/cfd/oanda/JP225USD>`_   |
+------------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+
| `NAS100USD <https://www.quantconnect.com/data#symbol/cfd/oanda/NAS100USD>`_  | `NATGASUSD <https://www.quantconnect.com/data#symbol/cfd/oanda/NATGASUSD>`_ | `NL25EUR <https://www.quantconnect.com/data#symbol/cfd/oanda/NL25EUR>`_     | `SOYBNUSD <https://www.quantconnect.com/data#symbol/cfd/oanda/SOYBNUSD>`_   | `SPX500USD <https://www.quantconnect.com/data#symbol/cfd/oanda/SPX500USD>`_ |
+------------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+
| `SUGARUSD <https://www.quantconnect.com/data#symbol/cfd/oanda/SUGARUSD>`_    | `SG30SGD <https://www.quantconnect.com/data#symbol/cfd/oanda/SG30SGD>`_     | `UK100GBP <https://www.quantconnect.com/data#symbol/cfd/oanda/UK100GBP>`_   | `UK10YBGBP <https://www.quantconnect.com/data#symbol/cfd/oanda/UK10YBGBP>`_ | `US2000USD <https://www.quantconnect.com/data#symbol/cfd/oanda/US2000USD>`_ |
+------------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+
| `US30USD <https://www.quantconnect.com/data#symbol/cfd/oanda/US30USD>`_      | `USB02YUSD <https://www.quantconnect.com/data#symbol/cfd/oanda/USB02YUSD>`_ | `USB05YUSD <https://www.quantconnect.com/data#symbol/cfd/oanda/USB05YUSD>`_ | `USB10YUSD <https://www.quantconnect.com/data#symbol/cfd/oanda/USB10YUSD>`_ | `USB30YUSD <https://www.quantconnect.com/data#symbol/cfd/oanda/USB30YUSD>`_ |
+------------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+
| `WHEATUSD <https://www.quantconnect.com/data#symbol/cfd/oanda/WHEATUSD>`_    | `WTICOUSD <https://www.quantconnect.com/data#symbol/cfd/oanda/WTICOUSD>`_   | `XAGAUD <https://www.quantconnect.com/data#symbol/cfd/oanda/XAGAUD>`_       | `XAGCAD <https://www.quantconnect.com/data#symbol/cfd/oanda/XAGCAD>`_       | `XAGCHF <https://www.quantconnect.com/data#symbol/cfd/oanda/XAGCHF>`_       |
+------------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+
| `XAGEUR <https://www.quantconnect.com/data#symbol/cfd/oanda/XAGEUR>`_        | `XAGGBP <https://www.quantconnect.com/data#symbol/cfd/oanda/XAGGBP>`_       | `XAGHKD <https://www.quantconnect.com/data#symbol/cfd/oanda/XAGHKD>`_       | `XAGJPY <https://www.quantconnect.com/data#symbol/cfd/oanda/XAGJPY>`_       | `XAGNZD <https://www.quantconnect.com/data#symbol/cfd/oanda/XAGNZD>`_       |
+------------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+
| `XAGSGD <https://www.quantconnect.com/data#symbol/cfd/oanda/XAGSGD>`_        | `XAGUSD <https://www.quantconnect.com/data#symbol/cfd/oanda/XAGUSD>`_       | `XAUAUD <https://www.quantconnect.com/data#symbol/cfd/oanda/XAUAUD>`_       | `XAUCAD <https://www.quantconnect.com/data#symbol/cfd/oanda/XAUCAD>`_       | `XAUCHF <https://www.quantconnect.com/data#symbol/cfd/oanda/XAUCHF>`_       |
+------------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+
| `XAUEUR <https://www.quantconnect.com/data#symbol/cfd/oanda/XAUEUR>`_        | `XAUGBP <https://www.quantconnect.com/data#symbol/cfd/oanda/XAUGBP>`_       | `XAUHKD <https://www.quantconnect.com/data#symbol/cfd/oanda/XAUHKD>`_       | `XAUJPY <https://www.quantconnect.com/data#symbol/cfd/oanda/XAUJPY>`_       | `XAUNZD <https://www.quantconnect.com/data#symbol/cfd/oanda/XAUNZD>`_       |
+------------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+
| `XAUSGD <https://www.quantconnect.com/data#symbol/cfd/oanda/XAUSGD>`_        | `XAUUSD <https://www.quantconnect.com/data#symbol/cfd/oanda/XAUUSD>`_       | `XAUXAG <https://www.quantconnect.com/data#symbol/cfd/oanda/XAUXAG>`_       | `XCUUSD <https://www.quantconnect.com/data#symbol/cfd/oanda/XCUUSD>`_       | `XPDUSD <https://www.quantconnect.com/data#symbol/cfd/oanda/XPDUSD>`_       |
+------------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+
| `XPTUSD <https://www.quantconnect.com/data#symbol/cfd/oanda/XPTUSD>`_        |                                                                             |                                                                             |                                                                             |                                                                             |
+------------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+-----------------------------------------------------------------------------+

To use OANDA Brokerage FX products in QuantConnect, you must specify the QuantConnect symbol, not the OANDA symbol. CFD data must be added in the Initialize() method manually. Specify ``Market.Oanda`` to backtest on OANDA historical data.

.. tabs::

   .. code-tab:: c#

        // Manual add symbols required in your initialize method:
        public override void Initialize() {
            AddCfd("AU200AUD", Resolution.Minute, Market.Oanda);
        }
        // Access data via dedicated event handlers:
        public void OnData(TradeBars data) {
            data["AU200AUD"].Close;
        }
        // Access data via grouped time slice method handlers:
        public override void OnData(Slice data) {
            data.Bars["AU200AUD"].Close;
        }

   .. code-tab:: py

        # Manual add symbols required in your initialize method:
        def Initialize(self):
            self.AddCfd("AU200AUD", Resolution.Minute, Market.Oanda)

        # Access data via grouped time slice method handlers:
        def OnData(self, data):
            data.Bars["AU200AUD"].Close;

|

Timezone
========

Oanda CFD data is set in the timezone in which the contract is listed. The CFD exchange timezones include New York, Chicago, Sydney, Berlin, London, Zurich, Hong Kong, Amsterdam, and UTC Time. When accessing CFD data, make sure to account for the different time zones.


|

About the Provider
==================

.. figure:: https://cdn.quantconnect.com/web/i/providers/algoseek.png
   :width: 200
   :align: center

`OANDA <https://www.oanda.com/>`__ uses innovative computer and financial technology to provide Internet-based forex trading and currency information services to everyone, from individuals to large corporations, from portfolio managers to financial institutions. OANDA is a market maker and a trusted source for currency data. It has access to one of the world's largest historical, high frequency, filtered currency databases.

