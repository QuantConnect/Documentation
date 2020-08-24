.. _algorithm-reference-universes:

=========
Universes
=========

|

Demonstration Algorithms
========================

.. list-table::
   :header-rows: 1

   * - C#
     - Python
   * - `CoarseFundamentalTop3DollarVolumeAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/CoarseFundamentalTop3Algorithm.cs>`_
     - `CoarseFundamentalTop3DollarVolumeAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/CoarseFundamentalTop3Algorithm.py>`_
   * - `EmaCrossUniverseSelectionAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/EmaCrossUniverseSelectionAlgorithm.cs>`_
     - `EmaCrossUniverseSelectionAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/EmaCrossUniverseSelectionAlgorithm.py>`_
   * - `DropboxUniverseSelectionAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/DropboxUniverseSelectionAlgorithm.cs>`_
     - `DropboxUniverseSelectionAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/DropboxUniverseSelectionAlgorithm.py>`_
   * - `WeeklyUniverseSelectionRegressionAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/WeeklyUniverseSelectionRegressionAlgorithm.cs>`_
     - `WeeklyUniverseSelectionRegressionAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/WeeklyUniverseSelectionRegressionAlgorithm.py>`_
   * - `CoarseFineFundamentalComboAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/CoarseFineFundamentalComboAlgorithm.cs>`_
     - `CoarseFineFundamentalComboAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/CoarseFineFundamentalComboAlgorithm.py>`_

|

Introduction
============

Universe selection is the process of screening or filtering the assets you'd like to trade by some predetermined formula. This helps to avoid `selection bias <https://en.wikipedia.org/wiki/Selection_bias>`_ in your algorithm. The following section introduces how to use the QuantConnect Universe Selection API.

QuantConnect provides two universes; Coarse Universe, and Fine Universe for the US Equities Market. The QuantConnect Universe data is refreshed every day. You can also create custom universes of data which we'll explore later in this section.

|

How Does Universe Selection Work
================================

Universe Selection sends a large data set into a filter function. After passing through your filters, the algorithm needs you to return an array of ``Symbol`` objects. LEAN automatically subscribes to these new symbols and adds them to your algorithm.

.. figure:: https://cdn.quantconnect.com/docs/i/filters.png

    QuantConnect Coarse and Fine Universe Selection

Your algorithm can do almost anything inside your filter functions, but the goal should be to narrow down the 8,000 daily stocks to a few which are most applicable for your algorithm.

The first stage of the filter is called "Coarse Universe Selection". This is for roughly filtering down the universe by simple properties like price and volume. The *result* of this is piped into the Fine Universe Selection function to perform selection on fundamental data like PE Ratio and Earnings.

When you deselect an asset you currently own or have open orders, it will continue receiving data. This is to ensure the portfolio models are accurate. You can use the algorithm property ``self.ActiveSecurities`` in Python or ``ActiveSecurities`` in C# to get the list of assets currently in a universe. Additionally, even once removed from the universe the security object in the Securties collection is maintained for record keeping purposes (e.g. total fees accrued, volume traded).

By default, assets selected by universe selection are requested with minute resolution data. You can change the default selection by adjusting the ``UniverseSettings property``, which we'll dive into below.

|

Coarse Universe Selection
=========================

Coarse Universe selection allows you to pick a set of stocks by its volume, price, or whether it has fundamental data. This is helpful to narrow down your universe to liquid assets, or assets which pass a technical indicator filter.


To use a coarse universe, you must request it using an ``AddUniverse()`` call from the ``Initialize()`` method of your algorithm. You should pass in a function that will be used to filter the stocks down to the assets you are interested in using.

.. tabs::

   .. code-tab:: c#

        public class MyCoarseUniverseAlgorithm : QCAlgorithm {
            public override void Initialize() {
                AddUniverse(MyCoarseFilterFunction);
            }
            // Coarse Filter Function accepts a list of CoarseFundamental Objects.
            IEnumerable<Symbol> MyCoarseFilterFunction(IEnumerable<CoarseFundamental> coarse) {

            }
        }

   .. code-tab:: py

        class MyCoarseUniverseAlgorithm(QCAlgorithm):
             def Initialize(self):
                 self.AddUniverse(self.MyCoarseFilterFunction)

            def MyCoarseFilterFunction(self, coarse):
                 pass

The coarse filter function is provided a list of ``CoarseFundamental`` objects. The most important properties of this object are: ``Price``, ``DollarVolume`` and ``HasFundamentaData``. Typical examples of filter functions you might want sound like:

**Example 1: Take 500 stocks worth more than $10, with more than $10M daily trading volume.**

The most common use case is selecting a lot of liquid stocks. With coarse this is simple and fast. This example below of a coarse filter function selects the top most liquid 500 stocks over $10 per share.

.. tabs::

   .. code-tab:: c#

        IEnumerable<Symbol> MyCoarseFilterFunction(IEnumerable<CoarseFundamental> coarse) {
            // Linq makes this a piece of cake;
            var stocks = (from c in coarse
                where c.DollarVolume > 10000000 &&
                      c.Price > 10
                orderby c.DollarVolume descending
                select c.Symbol).Take(500).ToList();
            return stocks;
        }

   .. code-tab:: py

        def MyCoarseFilterFunction(self, coarse):
                 sortedByDollarVolume = sorted(coarse, key=lambda x: x.DollarVolume, reverse=True)
                 filtered = [ x.Symbol for x in sortedByDollarVolume
                              if x.Price > 10 and x.DollarVolume > 10000000 ]
                 return filtered[:500]

**Example 2: Take 10 stocks above their 200-Day EMA with more than $1B daily trading volume.**

Another common request is to filter the universe by a technical indicator, such as only picking those above their 200-day EMA. The coarse fundamental object has adjusted price and volume information so we can do any price related analysis and return the symbols which pass our filter.

.. tabs::

   .. code-tab:: c#

        ConcurrentDictionary<Symbol, SelectionData>
            _stateData = new ConcurrentDictionary<Symbol, SelectionData>();

        // Coarse filter function
        IEnumerable<Symbol> MyCoarseFilterFunction(IEnumerable<CoarseFundamental> coarse) {
            // Linq makes this a piece of cake;
            var stocks = (from c in coarse
                let avg = _stateData.GetOrAdd(c.Symbol, sym => new SelectionData(200))
                where avg.Update(c.EndTime, c.AdjustedPrice)
                where c.DollarVolume > 1000000000 &&
                      c.Price > avg.Ema
                orderby c.DollarVolume descending
                select c.Symbol).Take(10).ToList();
            return stocks;
        }

   .. code-tab:: py

        # setup state storage in initialize method
        self.stateData = { };

        def MyCoarseFilterFunction(self, coarse):
            # We are going to use a dictionary to refer the object that will keep the moving averages
            for c in coarse:
                if c.Symbol not in self.stateData:
                    self.stateData[c.Symbol] = SelectionData(c.Symbol, 200)

                # Updates the SymbolData object with current EOD price
                avg = self.stateData[c.Symbol]
                avg.update(c.EndTime, c.AdjustedPrice, c.DollarVolume)

            # Filter the values of the dict to those above EMA and more than $1B vol.
            values = [x for x in self.stateData.values() if x.is_above_ema and x.volume > 1000000000]

            # sort by the largest in volume.
            values.sort(key=lambda x: x.volume, reverse=True)

            # we need to return only the symbol objects
            return [ x.symbol for x in values[:10] ]

In this example, we've used a new defined SelectionData class. This is a tidy way to group variables for our universe selection and update any indicators all in a few lines of code. We highly recommend following this pattern to keep your algorithm tidy and bug free! Below we've put an example of a ``SelectionData`` class, but you can make this whatever you need to store your custom universe filters.

.. tabs::

   .. code-tab:: c#

        // example selection data class
        private class SelectionData
        {
            // variables you need for selection
            public readonly ExponentialMovingAverage Ema;

            // initialize your variables and indicators.
            public SelectionData(int period)
            {
                Ema = new ExponentialMovingAverage(period);
            }

            // update your variables and indicators with the latest data.
            // you may also want to use the History API here.
            public bool Update(DateTime time, decimal value)
            {
                return Ema.Update(time, value);
            }
        }

   .. code-tab:: py

        class SelectionData(object):
            def __init__(self, symbol, period):
                self.symbol = symbol
                self.ema = ExponentialMovingAverage(period)
                self.is_above_ema = False
                self.volume = 0

            def update(self, time, price, volume):
                self.volume = volume
                if self.ema.Update(time, price):
                    self.is_above_ema = price > ema

**Example 3: Take 10 stocks the furthest above their 10 day SMA of volume.**

Getting the 10-day SMA stock volume is the same process as applying other indicators to data from Example 2. First, you should define a SelectionData class which performs the averaging. For this example, the following class will serve this purpose:

.. tabs::

   .. code-tab:: c#

        private class SelectionData
        {
            public readonly Symbol Symbol;
            public readonly SimpleMovingAverage VolumeSma;
            public decimal VolumeRatio;
            public SelectionData(Symbol symbol, int period)
            {
                Symbol = symbol;
                VolumeSma = new SimpleMovingAverage(period);
            }
            public bool Update(DateTime time, decimal value)
            {
                var ready = VolumeSma.Update(time, value);
                VolumeRatio = value / VolumeSma;
                return ready;
            }
        }

   .. code-tab:: py

        class SelectionData(object):
            def __init__(self, symbol, period):
                self.symbol = symbol
                self.volume = 0
                self.volume_ratio = 0
                self.sma = SimpleMovingAverage(period)

            def update(self, time, price, volume):
                self.volume = volume
                if self.sma.Update(time, volume):
                    # get ratio of this volume bar vs previous 10 before it.
                    self.volume_ratio = volume / self.sma.Current.Value

With this helper, we've defined a ratio of today's volume to the historical volumes. We can use this ratio to select assets that are above their 10-day simple moving average and sort the selection by the ones which have had the biggest jump since yesterday.

We could use this Selection data like so:

.. tabs::

   .. code-tab:: c#

        IEnumerable<Symbol> MyCoarseFilterFunction(IEnumerable<CoarseFundamental> coarse) {
            var stocks = (from c in coarse
                let avg = _stateData.GetOrAdd(c.Symbol, sym => new SelectionData(10))
                where avg.Update(c.EndTime, c.Volume)
                where c.Volume > avg.VolumeSma
                orderby avg.VolumeRatio descending
                select c.Symbol).Take(10).ToList();
            return stocks;
        }

   .. code-tab:: py

        def CoarseFilterFunction(self, coarse):
                for c in coarse:
                    if c.Symbol not in self.stateData:
                        self.stateData[c.Symbol] = SelectionData(c.Symbol, 10)
                    avg = self.stateData[c.Symbol]
                    avg.update(c.EndTime, c.AdjustedPrice, c.DollarVolume)

                # filter the values of selectionData(sd) above SMA
                values = [sd for sd in self.stateData.values() if sd.volume > sd.sma.Current.Value and sd.volume_ratio > 0]

                # sort sd by the largest % jump in volume.
                values.sort(key=lambda sd: sd.volume_ratio, reverse=True)

                # return the top 10 symbol objects
                return [ sd.symbol for sd in values[:10] ]

**Example 4: Take top 10 "fastest moving" stocks with a 50-Day EMA > 200 Day EMA.**

Complex universe filters can be constructed using the SelectionData helper class pattern. We have implemented a full example of this case in Github, which you can view `here <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/EmaCrossUniverseSelectionAlgorithm.cs>`_ . We've also made a Boot Camp for this example, which you can do `here <https://www.quantconnect.com/terminal/#lesson-271/200-50-EMA-Momentum-Universe>`__.

|

Fundamentals Selection
======================

The universe API supports performing universe selection based on corporate fundamental data. This data is powered by `Morningstar <https://www.quantconnect.com/data#provider/morningstar>`_ and includes approximately 5,000 tickers with 900 properties each. The data comes delivered as a ``FineFundamental`` type.

Due to the sheer volume of information, Fundamental selection is performed on the output of the coarse universe. You can think of this as a 2-stage filter; first, coarse universe can select all of the liquid assets, then fine fundamental universe can select those which meet your targets.

For the ``FineFundamental`` properties, please check out our `data library <https://www.quantconnect.com/data#fundamentals/usa/morningstar>`_ page.

.. note:: **Tip:**

          Only 5,000 assets have fundamental data. When working with fundamental data, you should always include the "HasFundamentalData" filter in your Coarse Universe filter. See the example below for how to do this in your algorithm.

**Requesting a Fundamental Universe**

To request a fundamental universe, pass a second filter-function into the ``AddUniverse()`` method. The second function handles the filtering of your FineFundamental objects:

.. tabs::

   .. code-tab:: c#

        public class MyUniverseAlgorithm : QCAlgorithm {
            public override void Initialize() {
                AddUniverse(MyCoarseFilterFunction, MyFineFundamentalFilterFunction);
            }
            // filter based on CoarseFundamental
            IEnumerable<Symbol> MyCoarseFilterFunction(IEnumerable<CoarseFundamental> coarse) {
                 // return list of symbols
            }
            // filter based on FineFundamental
            public IEnumerable<Symbol> FineSelectionFunction(IEnumerable<FineFundamental> fine)
            {
                // return list of symbols
            }
        }

   .. code-tab:: py

        class MyUniverseAlgorithm(QCAlgorithm):
             def Initialize(self):
                 self.AddUniverse(self.MyCoarseFilterFunction, self.MyFineFundamentalFunction)

            def MyCoarseFilterFunction(self, coarse):
                 pass

            def MyFineFundamentalFunction(self, fine):
                 pass

**Example 1: From the top 50 stocks with the highest volume, take 10 with lowest PE-ratio.**

The simplest example of accessing the fundamental object would be harnessing the iconic PE ratio for a stock. This is a ratio of the price it commands to the earnings of a stock. The lower the PE ratio for a stock, the more affordable it appears.

.. tabs::

   .. code-tab:: c#

        // Take the top 50 by dollar volume using coarse
        // Then the top 10 by PERatio using fine
        AddUniverse(
            coarse => {
                return (from c in coarse
                    where c.Price > 10 && c.HasFundamentalData
                    orderby c.DollarVolume descending
                    select c.Symbol).Take(50);
            },
            fine => {
                return (from f in fine
                    orderby f.ValuationRatios.PERatio ascending
                    select f.Symbol).Take(10);
            });

   .. code-tab:: py

        # In Initialize:
        self.AddUniverse(self.CoarseSelectionFunction, self.FineSelectionFunction)

        def CoarseSelectionFunction(self, coarse):
            sortedByDollarVolume = sorted(coarse, key=lambda x: x.DollarVolume, reverse=True)
            filtered = [ x.Symbol for x in sortedByDollarVolume if x.HasFundamentalData ]
            return filtered[:50]

        def FineSelectionFunction(self, fine):
            sortedByPeRatio = sorted(fine, key=lambda x: x.ValuationRatios.PERatio, reverse=False)
            return [ x.Symbol for x in sortedByPeRatio[:10] ]

There are 900 properties you can use to perform your own filtering. We recommend you review the `data library <https://www.quantconnect.com/data#fundamentals/usa/morningstar>`_ page dedicated to this data to fully understand each property.

**Example 2: The "QC-500", 500 companies which are liquid, profitable and more than 1B volume.**

Due to licensing restrictions, QuantConnect does not have the iconic S&P500 index list, however, we have reconstructed a homemade version which is a 90% replication which we call the QC-500. The QC-500 is too large to paste into this documentation, but we have open sourced the implementation for educational purposes. For more information, see the `QC500 example algorithm <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/ConstituentsQC500GeneratorAlgorithm.py>`_.

**Practical Limitations**

Like coarse universes, fine universes allow you to select an unlimited universe of symbols to analyze. Each asset added consumes approximately 5MB of RAM, so you may quickly run out of memory if your universe filter selects many symbols. QuantConnect provides unlimited backtesting and a free 8GB of RAM per backtest. If you run into memory issues, you can increase this with a subscription or memory packs. You can help keep your algorithm fast and efficient by only subscribing to the assets you need.

|

Universe Settings
=================

Universes are created according to default settings objects. You can configure these settings objects to create your own customized universes. Below is the UniverseSettings object and its default settings:

.. tabs::

   .. code-tab:: c#

        //Popular universe settings:
        UniverseSettings.Resolution      // What resolution should added assets use
                        .Leverage        // What leverage should assets use in the universe?
                        .FillForward     // Should asset data fill forward?
                        .MinimumTimeInUniverse // Minimum time assets should be in universe
                        .ExtendedMarketHours  // Should assets also feed extended market hours?

   .. code-tab:: py

        //Popular universe settings:
        self.UniverseSettings.Resolution      // What resolution should added assets use
                        .Leverage        // What leverage should assets use in the universe?
                        .FillForward     // Should asset data fill forward?
                        .MinimumTimeInUniverse // Minimum time assets should be in universe
                        .ExtendedMarketHours  // Should assets also feed extended market hours?

These settings should be configured in your ``Initialize()`` method before you request your universe. They are globals, so they will apply to all universes you create.

.. tabs::

   .. code-tab:: c#

        public class MyCustomSettingsUniverseAlgorithm : QCAlgorithm {
            public override void Initialize()
            {
                UniverseSettings.Resolution = Resolution.Second;
                AddUniverse(MySecondResolutionCoarseFilterFunction);
            }
        }

   .. code-tab:: py

    class MyCustomSettingsUniverseAlgorithm(QCAlgorithm):
         def Initialize(self):
             # Request second resolution data. This will be slow!
             self.UniverseSettings.Resolution = Resolution.Second
             self.AddUniverse(self.MySecondResolutionCoarseFilterFunction)

|

.. _algorithm-reference-universes-configuring-universe-securities:

Configuring Universe Securities
===============================

When adding securities from the universe, some algorithms need raw or partially adjusted price data. This can be controlled by the ``SetSecurityInitializer()`` method. With this method, you can apply any fill model or special data requests on a per-security basis.

The most common request is for raw, unadjusted price information. This can be achieved by combining the SetSecurityInitializer method with ``SetDataNormalizationMode()`` method. As each security is added to the universe, its data will be set to any of the ``DataNormalizationMode`` enum values.

.. tabs::

   .. code-tab:: c#

        //In Initialize
        SetSecurityInitializer(CustomSecurityInitializer);

        private void CustomSecurityInitializer(Security security)
        {
            //Initialize the security with raw prices
            security.SetDataNormalizationMode(DataNormalizationMode.Raw);
        }

   .. code-tab:: py

        #In Initialize
        self.SetSecurityInitializer(self.CustomSecurityInitializer)

        def CustomSecurityInitializer(self, security):
            '''Initialize the security with raw prices'''
            security.SetDataNormalizationMode(DataNormalizationMode.Raw)

For simple requests, you can use the functional implementation of the security initializer. This lets you configure and return the security object with 1 line of code:

.. tabs::

   .. code-tab:: c#

        //Most common request; requesting raw prices for universe securities.
        SetSecurityInitializer(x => x.SetDataNormalizationMode(DataNormalizationMode.Raw));

   .. code-tab:: py

        # Most common request; requesting raw prices for universe securities.
        self.SetSecurityInitializer(lambda x: x.SetDataNormalizationMode(DataNormalizationMode.Raw))

|

Security Changed Events
=======================

When universe contents are changed (securities are added or removed from the algorithm), we generate an ``OnSecuritiesChanged`` event. This allows your algorithm to know the changes in the universe state. The event passes in the `SecurityChanges <https://www.quantconnect.com/lean/docs#>`_ object containing references to the Added and Removed securities.

To monitor these events, you can bind to the ``OnSecuritiesChanged`` Event handler:

.. tabs::

   .. code-tab:: c#

        public override void OnSecuritiesChanged(SecurityChanges changes)
        {
            if (changes.AddedSecurities.Count > 0)
            {
                Debug("Securities added: " +
                      string.Join(",", changes.AddedSecurities.Select(x => x.Symbol.Value)));
            }
            if (changes.RemovedSecurities.Count > 0)
            {
                Debug("Securities removed: " +
                      string.Join(",", changes.RemovedSecurities.Select(x => x.Symbol.Value)));
            }
        }

   .. code-tab:: py

        def OnSecuritiesChanged(self, changes):
            self._changes = changes
            self.Log(f"OnSecuritiesChanged({self.UtcTime}):: {changes}")

These events are tracked automatically, and a list of current securities is provided by the ``ActiveSecurities`` property. This is a dictionary of Security objects which are currently in your universe. See more in the :ref:`Securities and Portfolio <algorithm-reference-securities-and-portfolio>` documentation.

|

Universe Creation Short Cuts
============================

Popular simple universe filters are pre-built for you to use in a single line of code. These shortcuts can be used to quickly choose your universe.

.. tabs::

   .. code-tab:: c#

        // Helper: Add US-equity universe for the top 50 stocks by dollar volume
        AddUniverse(Universe.DollarVolume.Top(50));

        // Helper: Add US-equity universe for the bottom 50 stocks by dollar volume
        AddUniverse(Universe.DollarVolume.Bottom(50));

        // Helper: Add US-equity universe for the 90th dollar volume percentile
        AddUniverse(Universe.DollarVolume.Percentile(90));

        // Helper: Add US-equity universe for stocks between the 70th and 80th dollar volume percentile
        AddUniverse(Universe.DollarVolume.Percentile(70, 80));

   .. code-tab:: py

        // Helper: Add US-equity universe for the top 50 stocks by dollar volume
        self.AddUniverse(self.Universe.DollarVolume.Top(50))

        // Helper: Add US-equity universe for the bottom 50 stocks by dollar volume
        self.AddUniverse(self.Universe.DollarVolume.Bottom(50))

        // Helper: Add US-equity universe for the 90th dollar volume percentile
        self.AddUniverse(self.Universe.DollarVolume.Percentile(90))

        // Helper: Add US-equity universe for stocks between the 70th and 80th dollar volume percentile
        self.AddUniverse(self.Universe.DollarVolume.Percentile(70, 80))

Custom universes allow you to perform selection on your own datasets. Custom universe types extend from ``BaseData``, so implement a ``Reader()`` method which parses the lines of the file.

Each of the custom universe data points is 1 line of the source file. The Reader method will be called repeatedly until the date/time advances, or the end of file is reached. This way you can group universe data and pass it as a single collection into the filter function.

**Adding a Custom Universe**

.. tabs::

   .. code-tab:: c#

        // Add custom universe type and define the filter function.
        AddUniverse("myCustomUniverse", Resolution.Daily, nyseTopGainersList => {
              return from singleStockData in nyseTopGainersList
                     where singleStockData.Rank > 5
                     select singleStockData.Symbol;
        });

   .. code-tab:: py

        # add the custom universe in initialize
        self.AddUniverse(NyseTopGainers, "myCustomUniverse", Resolution.Daily, self.nyseTopGainers)
        # filter function using your custom data
        def nyseTopGainers(self, data):
            return [ x.Symbol for x in data if x["Rank"] > 5 ]

**Defining Custom Universe Type**

Custom universes need a type defined to perform the parsing of the file. This pattern is almost identical to :ref:`importing custom <algorithm-reference-importing-custom-data>` to your algorithm, except the data is being used for choosing the universe data subscription instead of a price feed.

.. tabs::

   .. code-tab:: c#

        //Example custom universe data; it is virtually identical to other custom data types.
        public class NyseTopGainers : BaseData
        {
            public int TopGainersRank;
            public override DateTime EndTime {
                // define end time as exactly 1 day after Time
            get { return Time + QuantConnect.Time.OneDay; }
            set { Time = value - QuantConnect.Time.OneDay; }
            }

            public override SubscriptionDataSource GetSource(SubscriptionDataConfig config, DateTime date, bool isLiveMode) {
                return new SubscriptionDataSource(@"your-remote-universe-data", SubscriptionTransportMedium.RemoteFile);
             }

             public override BaseData Reader(SubscriptionDataConfig config, string line, DateTime date, bool isLiveMode) {
                 // Generate required data, then return an instance of your class.
                return new NyseTopGainers {
                    Symbol = Symbol.Create(symbolString, SecurityType.Equity, Market.USA),
                    Time = date,
                    TopGainersRank = rank
                };
            }
        }

   .. code-tab:: py

        # Example custom universe data; it is virtually identical to other custom data types.
        class NyseTopGainers(PythonData):

            def GetSource(self, config, date, isLiveMode):
                return SubscriptionDataSource(@"your-remote-universe-data", SubscriptionTransportMedium.RemoteFile)

            def Reader(self, config, line, date, isLiveMode):
                # Generate required data, then return an instance of your class.
                nyse = NyseTopGainers()
                nyse.Time = date
                # define end time as exactly 1 day after Time
                nyse.EndTime = nyse.Time + timedelta(1)
                nyse.Symbol = Symbol.Create(symbolString, SecurityType.Equity, Market.USA)
                nyse["Rank"] = rank
                return nyse

|

Custom Universe Selection
=========================

Custom universes allow you to perform selection on your own datasets. Custom universe types extend from ``BaseData``, so implement a ``Reader()`` method which parses the lines of the file.

Each of the custom universe data points is 1 line of the source file. The Reader method will be called repeatedly until the date/time advances, or the end of file is reached. This way you can group universe data and pass it as a single collection into the filter function.

**Adding a Custom Universe**

.. tabs::

   .. code-tab:: c#

        // Add custom universe type and define the filter function.
        AddUniverse("myCustomUniverse", Resolution.Daily, nyseTopGainersList => {
              return from singleStockData in nyseTopGainersList
                     where singleStockData.Rank > 5
                     select singleStockData.Symbol;
        });

   .. code-tab:: py

        # add the custom universe in initialize
        self.AddUniverse(NyseTopGainers, "myCustomUniverse", Resolution.Daily, self.nyseTopGainers)
        # filter function using your custom data
        def nyseTopGainers(self, data):
            return [ x.Symbol for x in data if x["Rank"] > 5 ]

**Defining Custom Universe Type**

Custom universes need a type defined to perform the parsing of the file. This pattern is almost identical to :ref:`importing custom data <algorithm-reference-importing-custom-data>` to your algorithm, except the data is being used for choosing the universe data subscription instead of a price feed.

.. tabs::

   .. code-tab:: c#

        //Example custom universe data; it is virtually identical to other custom data types.
        public class NyseTopGainers : BaseData
        {
            public int TopGainersRank;
            public override DateTime EndTime {
                // define end time as exactly 1 day after Time
            get { return Time + QuantConnect.Time.OneDay; }
            set { Time = value - QuantConnect.Time.OneDay; }
            }

            public override SubscriptionDataSource GetSource(SubscriptionDataConfig config, DateTime date, bool isLiveMode) {
                return new SubscriptionDataSource(@"your-remote-universe-data", SubscriptionTransportMedium.RemoteFile);
             }

             public override BaseData Reader(SubscriptionDataConfig config, string line, DateTime date, bool isLiveMode) {
                 // Generate required data, then return an instance of your class.
                return new NyseTopGainers {
                    Symbol = Symbol.Create(symbolString, SecurityType.Equity, Market.USA),
                    Time = date,
                    TopGainersRank = rank
                };
            }
        }

   .. code-tab:: py

        # Example custom universe data; it is virtually identical to other custom data types.
        class NyseTopGainers(PythonData):

            def GetSource(self, config, date, isLiveMode):
                return SubscriptionDataSource(@"your-remote-universe-data", SubscriptionTransportMedium.RemoteFile)

            def Reader(self, config, line, date, isLiveMode):
                # Generate required data, then return an instance of your class.
                nyse = NyseTopGainers()
                nyse.Time = date
                # define end time as exactly 1 day after Time
                nyse.EndTime = nyse.Time + timedelta(1)
                nyse.Symbol = Symbol.Create(symbolString, SecurityType.Equity, Market.USA)
                nyse["Rank"] = rank
                return nyse

|

Option Universes
================

When you add an option to the algorithm it adds many individual option contract securities. These are modelled as a "universe" of option contracts. We provide the ``SetFilter`` method to help narrow the option strike and expiry dates down to a range you are interested in.

For more information on selecting options universes, see the :ref:`Options <data-library-options>` section in Data Library documentation.

|

Future Universes
================

When you add a futures asset to your algorithm, it adds all the contracts which match your filter as a universe of futures contracts in a similar way to option. The primary difference is that futures don't have a strike price, so the universe filter is primarily focused on the future expiration date.

For more information on selecting futures universes, see the :ref:`Futures <data-library-futures>` section in Data Library documentation.