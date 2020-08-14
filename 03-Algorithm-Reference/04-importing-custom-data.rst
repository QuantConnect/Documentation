===========================================
Algorithm Reference - Importing Custom Data
===========================================

|

Demonstration Algorithms
========================

.. list-table::
   :header-rows: 1

   * - C#
     - Python
   * - `CustomDataBitcoinAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/CustomDataBitcoinAlgorithm.cs>`_
     - `CustomDataBitcoinAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/CustomDataBitcoinAlgorithm.py>`_
   * - `CustomDataNIFTYAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/CustomDataNIFTYAlgorithm.cs>`_
     - `CustomDataNIFTYAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/CustomDataNIFTYAlgorithm.py>`_
   * - `QuandlImporterAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/QuandlImporterAlgorithm.cs>`_
     - `QuandlImporterAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/QuandlImporterAlgorithm.py>`_
   * - `BubbleAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/BubbleAlgorithm.cs>`_
     - `BubbleAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/BubbleAlgorithm.py>`_
   * - `BasicTemplateIntrinioEconomicData.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/BasicTemplateIntrinioEconomicData.cs>`_
     - `TiingoPriceAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/TiingoPriceAlgorithm.py>`_
   * - `TiingoPriceAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/TiingoPriceAlgorithm.cs>`_
     - `DropboxCoarseFineAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/DropboxCoarseFineAlgorithm.py>`_

|

Introduction
============

LEAN supports backtesting almost any external custom data source. To use this feature, you need to add the data during initialize using ``AddData<T>()`` (C#) / ``self.AddData()`` (Py) and instruct your algorithm on how to read your data. We provide helpers for popular data sources like Quandl and Intrinio, but if you are using your own format or server, you'll need to create a custom type.

|

Initializing Custom Data
========================

During initialize, your algorithm must use ``AddData<T>(string ticker, Resolution resolution = Resolution.Daily)`` (C#) / ``self.AddData(Type class, string ticker, Resolution resolution = Resolution.Daily)`` (Py). This gives LEAN the type factory to create the objects, the name of the data, and the resolution at which to poll the data to check for updates.

The framework checks for new data as instructed by the ``Resolution`` period; i.e. Resolution.Tick polls constantly, Resolution.Second polls every second, and Resolution.Minute polls every minute. Hourly and Daily Resolutions are polled every 30 minutes.

.. tabs::

   .. code-tab:: c#

        // In Initialize method:
        AddData<Weather>("KNYC", Resolution.Daily);

   .. code-tab:: py

        # In Initialize method:
        self.AddData(Weather, "KNYC", Resolution.Minute)

|

Creating and Reading Custom Data
================================

You must create a custom type to instruct LEAN where to get your data and how to read it. We support many different data types and formats. You can even change source locations for backtesting and live modes. All data must extend from ``BaseData`` and override the ``Reader`` and ``GetSource`` methods.

``GetSource`` instructs LEAN where to find your data. It must return a ``SubscriptionDataSource`` object containing the string URL to find your data, and the format of the data (SubscriptionTransportMedium RemoteFile or Rest). When the source returned changes URL, the data is downloaded again. This allows LEAN to cache large files and only download new data when requested. This also allows you to break up large intraday data into smaller daily files, speeding up the backtest.

When using ``SubscriptionTransportMedium.Rest`` the URL provided is polled at each Resolution time step and is assumed to be sufficient for 1-data point. This is generally intended for live data sources.

``Reader`` takes one line of data provided by the source and parses it into one of your custom objects (e.g. Weather in the code snippet). In addition to setting your custom type properties, you should also take care to set three required properties:

* Symbol - Should always be set to config.Symbol
* Time - Required synchronization of custom data
* Value - Required for purchasing and portfolio calculations

When there is no usable data in a line, your type should return null.

.. tabs::

   .. code-tab:: c#

        public class Weather : BaseData
        {
            public decimal MaxC = 0;
            public decimal MinC = 0;
            public string errString = "";

            public override SubscriptionDataSource GetSource(
                SubscriptionDataConfig config,
                DateTime date,
                bool isLive)
            {
                var source = "https://www.dropbox.com/s/8v6z949n25hyk9o/custom_weather_data.csv?dl=1";

                  return new SubscriptionDataSource(source,
                      SubscriptionTransportMedium.RemoteFile);
            }

            public override BaseData Reader(
                SubscriptionDataConfig config,
                string line,
                DateTime date,
                bool isLive)
            {
                if (string.IsNullOrWhiteSpace(line) ||
                    char.IsLetter(line[0]))
                    return null;

                var data = line.Split(',');

                return new Weather()
                {
                    // Make sure we only get this data AFTER trading day - don't want forward bias.
                    Time = DateTime.ParseExact(data[0], "yyyyMMdd", null).AddHours(20),
                    Symbol = config.Symbol,
                    MaxC = Convert.ToDecimal(data[1]),
                    Value = Convert.ToDecimal(data[2]),
                    MinC = Convert.ToDecimal(data[3]),
                };
            }
        }

   .. code-tab:: py

        class Weather(PythonData):
            ''' Weather based rebalancing'''

            def GetSource(self, config, date, isLive):
                source = "https://www.dropbox.com/s/8v6z949n25hyk9o/custom_weather_data.csv?dl=1"
                return SubscriptionDataSource(source, SubscriptionTransportMedium.RemoteFile);


            def Reader(self, config, line, date, isLive):
                # If first character is not digit, pass
                if not (line.strip() and line[0].isdigit()): return None

                data = line.split(',')
                weather = Weather()
                weather.Symbol = config.Symbol
                weather.Time = datetime.strptime(data[0], '%Y%m%d') + timedelta(hours=20) # Make sure we only get this data AFTER trading day - don't want forward bias.
                weather.Value = decimal.Decimal(data[2])
                weather["MaxC"] = float(data[1])
                weather["MinC"] = float(data[3])

                return weather

|

Loading Reference Data
======================

You may want to import a single static reference file - such as loading a daily symbol list, or an AI-training file. This is possible with the ``Download()`` method. The ``Download`` method downloads a URL and returns it as a string. It can take header settings for authentication, and a username and password for basic authentication.

.. tabs::

   .. code-tab:: c#

        // If using dropbox remember to add the &dl=1 to trigger a download
        var file = Download("https://www.dropbox.com?....&dl=1");

   .. code-tab:: py

        # If using dropbox remember to add the &dl=1 to trigger a download
        csv = self.Download("https://www.dropbox.com?....&dl=1")

        # read file (which needs to be a csv) to a pandas DataFrame. include following imports above
        # from io import StringIO
        # import pandas as pd
        df = pd.read_csv(StringIO(csv))

It is a common request to download data from a public Dropbox file. In this case, you should ensure you're downloading the direct file link - not the HTML page of the download. You can specify this by adding ``&dl=1`` to the end of the Dropbox download URL.

|

Intrinio Custom Data
====================

Intrinio is a third party aggregator platform like Quandl, which is able to serve paid datasets. They provide a large library of financial datasets that might be useful for your algorithm. To assist using Intrinio data in your algorithm, we've created an ``IntrinioEconomicData`` implementation, which grants access to their repository of economic data from the Federal Reserve Economic Data (FRED).

We've built helpers of the most requested symbols on the ``IntrinioEconomicDataSources`` class, but the full list of economic data series is available `here <https://docs.intrinio.com/master/economic-indices#home>`_.

.. tabs::

   .. code-tab:: c#

        // In Initialize method:
         AddData<IntrinioEconomicData>(IntrinioEconomicDataSources.Commodities.CrudeOilWTI, Resolution.Daily);

   .. code-tab:: py

        # In Initialize method:
        self.AddData(IntrinioEconomicData, "$DCOILWTICO", Resolution.Daily)

|

Tiingo Price Data
=================

Tiingo provides daily data for 64,000 securities, including 24,000 US stocks. QuantConnect has implemented a wrapper to their API for you to use their data for your backtests and live trading.

Like Quandl, Tiingo requires an authorization key to access their data. You can set this with the static ``SetAuthCode()`` method in your Initialize method. You can find your Tiingo access token on your `API/Token Page <https://api.tiingo.com/account/token>`_.

.. tabs::

   .. code-tab:: c#

        Tiingo.SetAuthCode("my-tiingo-api-token")

   .. code-tab:: py

        Tiingo.SetAuthCode("my-tiingo-api-token")

Once authorized, you can request tickers you need via the ``AddData()`` method:

.. tabs::

   .. code-tab:: c#

        AddData<TiingoDailyData>("AAPL", Resolution.Daily);

   .. code-tab:: py

        self.AddData(TiingoDailyData, "AAPL", Resolution.Daily)

To help you get started, we've implemented an `example algorithm <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/TiingoDailyDataAlgorithm.cs>`_ using Tiingo data and indicators.