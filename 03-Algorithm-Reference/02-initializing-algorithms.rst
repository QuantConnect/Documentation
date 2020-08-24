.. _algorithm-reference-initializing-algorithms:

=======================
Initializing Algorithms
=======================

|

Demonstration Algorithms
========================

.. list-table::
   :header-rows: 1

   * - C#
     - Python
   * - `BasicTemplateAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/BasicTemplateAlgorithm.cs>`_
     - `BasicTemplateAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/BasicTemplateAlgorithm.py>`_
   * - `BasicTemplateCryptoAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/BasicTemplateCryptoAlgorithm.cs>`_
     - `BasicTemplateCryptoAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/BasicTemplateCryptoAlgorithm.py>`_
   * - `BasicTemplateForexAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/BasicTemplateForexAlgorithm.cs>`_
     - `BasicTemplateForexAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/BasicTemplateForexAlgorithm.py>`_
   * - `BasicTemplateFuturesAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/BasicTemplateFuturesAlgorithm.cs>`_
     - `BasicTemplateFuturesAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/BasicTemplateFuturesAlgorithm.py>`_
   * - `BasicTemplateOptionsAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/BasicTemplateOptionsAlgorithm.cs>`_
     - `BasicTemplateOptionsAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/BasicTemplateOptionsAlgorithm.py>`_

|

Introduction
============

The Initialize method is called to set up your strategy. Here you can request data, set starting cash, or warm up periods. It is called just once at the start of your algorithm.

|

Setting Cash
============

In backtests you can set your starting capital using the ``self.SetCash(float cash)`` method in Python, or the ``SetCash(decimal cash)`` method in C#. In live trading this is ignored and your brokerage cash is used instead. In paper trading we set the cash to a fictional $100,000 USD.

.. tabs::

   .. code-tab:: c#

        SetCash(decimal cash)

   .. code-tab:: py

        self.SetCash(100000)

|

Setting Dates
=============

Backtesting uses the ``self.SetStartDate(int year, int month, int day)`` and ``self.SetEndDate(int year, int month, int day)`` methods in Python, or the ``SetStartDate(int year, int month, int day)`` and ``SetEndDate(int year, int month, int day)`` methods in C# to configure the backtest time range. If unspecified, the end date defaults to yesterday. In .NET languages, you can also use a DateTime object to set the dates.

.. tabs::

   .. code-tab:: c#

        SetStartDate(2013, 1, 1);         // Set start date to specific date.
        SetEndDate(2015, 1, 1);         // Set end date to specific date.
        SetEndDate(DateTime.Now.Date.AddDays(-1)); // Or use a relative date.

   .. code-tab:: py

        self.SetStartDate(2013,1,1)
        self.SetEndDate(2015,1,1)
        self.SetEndDate(datetime.now() - timedelta(1)) # Or use a relative date.

|

.. _algorithm-reference-initializing-algorithms-selecting-asset-data:

Selecting Asset Data
====================

Algorithms can manually subscribe to data for specific assets they need or use universes to choose groups of assets based on filtering criteria (e.g. all stocks with volumes greater than $10M/day). See more about Universes :ref:`here <algorithm-reference-universes>`.

To manually subscribe to a specific asset you can call the ``AddEquity()``, ``AddForex()``, ``AddCrypto()``, ``AddCfd()`` and ``AddOption()`` methods in your Initialize() method. There is no official limit to how much data you can ask for, but there are practical resource limitations.

QuantConnect supports international trading across multiple timezones and markets. The ``Market`` parameter is used to distinguish between the same tickers on different exchanges (e.g. FXCM and OANDA both offer EURUSD but have different rates).

QuantConnect provides 40TB of data. Check out more information about our data in our `data library <https://www.quantconnect.com/data>`_. The resolution of the data available depends on the type of data. For Equities, Futures, Forex, and Crypto we provide Tick, Second, Minute, Hourly, and Daily resolution. For Options, we only have minute bar data. These are specified by the ``Resolution`` enum.

If there is a gap in the data (e.g. because there are no trades), by default the data is still pumped into your strategy on each time step. This behavior is called "fillForward" and defaults to true. You can disable this by setting fillForward to false.

By default equity data in QuantConnect is Split and Dividend adjusted backwards in time to give smooth continuous prices. This allows easy use for indicators. Some algorithms need raw or partially adjusted price data. You can control this with the ``SetDataNormalizationMode()`` method. The ``DataNormalizationMode`` enum has the values Adjusted (default), Raw, SplitAdjusted, and TotalReturn. When data is set to Raw mode the dividends are paid as cash into your algorithm, and the splits are directly applied to your holding quantity.

+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Data Adjustment Modes                                                                                                                                                                                                                                                                              |
+====================+===============================================================================================================================================================================================================================================================================+
| Adjusted (default) | |``DataNormalizationMode.Adjusted``                                                                                                                                                                                                                                              |
|                    | |
|                    | |Splits and dividends are backwards adjusted into the price of the asset. The price today is identical to current market price. For more information on this see Investopedia's article `Adjusted Pricing <https://www.investopedia.com/terms/a/adjusted_closing_price.asp>`_. |
+--------------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Raw                | |``DataNormalizationMode.Raw``                                                                                                                                                                                                                                                   |
|                    | No modifications to the asset price at all. Dividends are paid in cash; splits are applied directly to your portfolio quantity.                                                                                                                                               |
+--------------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| SplitAdjusted      | ``DataNormalizationMode.SplitAdjusted``                                                                                                                                                                                                                                         |
|                    | Only equity splits are applied to the price adjustment, while dividends are still paid in cash to your portfolio. This allows for management of the dividend payments (e.g. reinvestment) while still giving a smooth curve for indicators.                                   |
+--------------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| TotalReturn        | ``DataNormalizationMode.TotalReturn``                                                                                                                                                                                                                                           |
|                    | Return of the investment adding the dividend sum to the initial asset price. For more information on this see Investopedia's article `Total Returns <https://www.investopedia.com/terms/t/totalreturn.asp>`_.                                                                 |
+--------------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+

If you have your own custom data you'd like to backtest against, check out the :ref:`custom data section <algorithm-reference-importing-custom-data>`.

.. code-block::

        // Complete Add Equity API - Including Default Parameters:
        AddEquity(string ticker, Resolution resolution = Resolution.Minute, string market = Market.USA, bool fillDataForward = true, decimal leverage = 0m, bool extendedMarketHours = false)

        //Complete Add Forex API - Including Default Parameters:
        AddForex(string ticker, Resolution resolution = Resolution.Minute, string market = Market.FXCM, bool fillDataForward = true, decimal leverage = 0m)

.. tabs::

   .. code-tab:: c#

        AddEquity("AAPL"); //Add Apple 1 minute bars (minute by default).
        AddForex("EURUSD", Resolution.Second); //Add EURUSD 1 second bars.
        //Setting the data normalization mode for the MSFT security to raw (pay dividends as cash)
        Securities["MSFT"].SetDataNormalizationMode(DataNormalizationMode.Raw);

   .. code-tab:: py

        self.AddEquity("SPY")  # Default to minute bars
        self.AddForex("EURUSD", Resolution.Second) # Set second bars.
        # Setting the data normalization mode for the MSFT security to raw (pay dividends as cash)
        self.Securities["SPY"].SetDataNormalizationMode(DataNormalizationMode.Raw);

|

Setting Indicators
==================

Indicators should be created and warmed up in the ``Initialize()`` method in most applications. For more details, please see the :ref:`Indicators <algorithm-reference-indicators>` section.

|

Setting Warm Up Period
======================

Often algorithms need some historical data to prime technical indicators, or populate historical data arrays. Using the ``SetWarmUp(TimeSpan period)`` or ``SetWarmUp(int barCount)`` methods you can specify a warm-up period for your algorithm which pumps in data from before the start date. During the warm-up period you cannot place a trade.

Algorithms can use the ``bool IsWarmingUp`` property to determine if the warm-up period has completed.

.. tabs::

   .. code-tab:: c#

        SetWarmUp(200); //Warm up 200 bars for all subscribed data.
        SetWarmUp(TimeSpan.FromDays(7)); //Warm up 7 days of data.

   .. code-tab:: py

        self.SetWarmUp(200) # Warm up 200 bars for all subscribed data.
        self.SetWarmUp(timedelta(7)) # Warm up 7 days of data.

|

Cash and Brokerage Models
=========================

In QuantConnect, we model your algorithm with margin-modeling by default, but you can select a cash account type. Cash accounts do not allow leveraged trading, whereas Margin accounts support 2-4x leverage on your account value. You can set your brokerage account type in your initialization with ``SetBrokerageModel(BrokerageName brokerage, AccountType account)``.

The ``BrokerageName`` enum supports values of Default, QuantConnectBrokerage, TradierBrokerage, InteractiveBrokersBrokerage, FxcmBrokerage, OandaBrokerage, Bitfinex, GDAX, Alpaca, and AlphaStreams. When setting the brokerage name, we also set the trading fee structures for that brokerage.

The ``AccountType`` enum supports values of Cash and Margin. When using cash, leverage is disabled by default, and the cash settlement period is set to 3 days for Equity securities. Margin accounts are settled immediately and have a leverage of 2.

Margin accounts with more than $25,000 in equity are eligible for pattern day trading margin limits. This increases your available leverage to 4x while the market is open and 2x overnight. To model this behavior in your algorithm, you must set your security ``MarginModel`` to ``PatternDayTradingMarginModel``.

See more about brokerage models in the :ref:`Reality Modeling <algorithm-reference-reality-modeling>` section.

.. tabs::

   .. code-tab:: c#

        //Brokerage model and account type:
        SetBrokerageModel(BrokerageName.InteractiveBrokersBrokerage, AccountType.Margin);

        //Add securities and if required set custom margin models
        var spy = AddEquity("SPY"); //Defaults to minute bars.
        spy.MarginModel = new PatternDayTradingMarginModel();

        // You can also create your own brokerage model: IBrokerageModel
        class MyBrokerage: DefaultBrokerage {
           // Custom implementation of brokerage here.
        }

        SetBrokerageModel(new MyBrokerage());

   .. code-tab:: py

        #Brokerage model and account type:
        self.SetBrokerageModel(BrokerageName.InteractiveBrokersBrokerage, AccountType.Cash)

        //Add securities and if required set custom margin models
        spy = self.AddEquity("SPY") # Default to minute bars
        spy.MarginModel = PatternDayTradingMarginModel()

|

Setting Benchmark
=================

You can set a custom benchmark for your algorithm using the ``SetBenchmark()`` method. This should be called in your Initialize() function.

.. tabs::

   .. code-tab:: c#

        // Defaults to Equity market
        SetBenchmark("IBM");

   .. code-tab:: py

        self.SetBenchmark("SPY")

|

Setting Time Zone
=================

The QuantConnect LEAN engine was designed to support international trading across multiple time zones and markets. Consequently, we need to define a reference time zone for the algorithm to set the ``Time``. By default, New York time is used.

You can set a different time zone for your convenience using the ``SetTimeZone()`` method. This should be called in your Initialize() function. This method accepts either a string following the `IANA Time Zone database <https://en.wikipedia.org/wiki/List_of_tz_database_time_zones>`_ convention or `NodaTime <https://nodatime.org/>`_.DateTimeZone objects. The ``TimeZones`` class provides access to common time zones.

.. tabs::

   .. code-tab:: c#

        SetTimeZone("Europe/London");
        SetTimeZone(NodaTime.DateTimeZone.Utc);
        SetTimeZone(TimeZones.Chicago);

   .. code-tab:: py

        self.SetTimeZone("Europe/London")
        self.SetTimeZone(NodaTime.DateTimeZone.Utc)
        self.SetTimeZone(TimeZones.Chicago)

The algorithm ``TimeZone`` may be different from the data time zone (e.g.: Forex trading). In this case it might appear like there is a lag between the algorithm time and the first bar of a history request, however, this is just the difference in time zone. All the data is internally synchronized in UTC time and arrives in the same "time slice" or ``Slice`` object. A slice is a sliver of time with all the data available for this moment.

To keep trades easy to compare between asset classes, we mark all orders in ``UtcTime``.