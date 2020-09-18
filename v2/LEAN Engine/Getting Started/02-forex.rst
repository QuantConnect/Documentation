=====
Forex
=====

|

Introduction
============

QuantQuote equity data is set in its local time, New York Time. This means that when accessing equity data, all data will be time stamped in New York Time.

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

QuantQuote equity data is set in its local time, New York Time. This means that when accessing equity data, all data will be time stamped in New York Time.

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

The `FXCM <https://www.fxcm.com/>`_ group of companies (collectively, the "FXCM Group") is a leading international provider of online foreign exchange (forex) trading, CFD trading, spread betting and related services to retail and institutional customers worldwide. Founded in 1999 and headquartered in New York, NY, FXCM has operating subsidiaries regulated in a number of jurisdictions, including the United Kingdom and Australia. We also maintain offices in Italy, France, Germany, and Greece.

At the heart of FXCM's client offering is No Dealing Desk forex trading. Clients benefit from FXCM's large network of forex liquidity providers enabling FXCM to offer competitive spreads on major currency pairs. Clients have the advantage of mobile trading, one-click order execution and trading from real-time charts. FXCM's U.K. subsidiary, Forex Capital Markets Limited, also offers CFD products with no re-quote trading and allows clients to trade oil, gold, silver and stock indices along with forex on one platform. In addition, FXCM offers educational courses on forex trading and provides access to exclusive tools through FXCM PLUS.

While FXCM has made every effort to ensure the accuracy of the information provided to QuantConnect, FXCM does not guarantee its accuracy, and will not accept liability for any loss or damage that may arise directly or indirectly from the content or your inability to access the website, for any delay in or failure of the transmission or the receipt of any instruction or notifications sent through this website. Nothing on this website shall be considered a solicitation to buy or an offer to sell any product or service to any person in any jurisdiction where such offer, solicitation, purchase or sale would be unlawful under the laws or regulations of such jurisdiction.

Trading forex/CFDs on margin carries a high level of risk and may not be suitable for all investors as you could sustain losses in excess of deposits. Leverage can work against you. Be aware and fully understand all risks associated with the market and trading. Before deciding to trade any products, carefully consider your financial situation and experience level. Seek advice from an independent financial advisor.