.. _algorithm-reference-indicators:

==========
Indicators
==========

|

Demonstration Algorithms
========================

.. list-table::
   :header-rows: 1

   * - C#
     - Python
   * - `IndicatorSuiteAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/IndicatorSuiteAlgorithm.cs>`_
     - `IndicatorSuiteAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/IndicatorSuiteAlgorithm.py>`_
   * - `EmaCrossUniverseSelectionAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/EmaCrossUniverseSelectionAlgorithm.cs>`_
     - `EmaCrossUniverseSelectionAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/EmaCrossUniverseSelectionAlgorithm.py>`_
   * - `MACDTrendAlgorithm.cs <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/MACDTrendAlgorithm.cs>`_
     - `MACDTrendAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/MACDTrendAlgorithm.py>`_
   * - `RegressionChannelAlgorithm <https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/RegressionChannelAlgorithm.cs>`_
     - `RegressionChannelAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/RegressionChannelAlgorithm.py>`_
   * -
     - `TalibIndicatorsAlgorithm.py <https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/TalibIndicatorsAlgorithm.py>`_

|

Introduction
============

We provide more than 100 technical indicators for you to use in your algorithm. These are provided in two ways: through helper short cut methods, and as class objects. Indicators created through the short cut methods have already been wired up to receive data and are "ready to use". A full list of the indicators and their properties can be found in the reference table below.

One key indicator to learn is the ``Identity`` indicator, which simply returns the value of the asset. This can be useful for combining indicators. For example:

.. tabs::

   .. code-tab:: c#

        var pep = Identity("PEP");     // Pepsi ticker
        var coke = Identity("KO");     // Coke ticker
        var delta = pep.Minus(coke);   // Difference between them

   .. code-tab:: py

        pep = Identity("PEP")   # Pepsi ticker
        coke = Identity("KO")   # Coke ticker
        delta = IndicatorExtensions.Minus(pep, coke)   # Difference between them

|

Indicator Ready
===============

Indicators are not ready when you first create them. The length of time it takes to trust the indicator values depends on the indicator period. In QuantConnect, we provide a shortcut to check the indicator status. ``Indicator.IsReady`` will return true when the indicator is ready to be used. eg. ``IsReady``.


.. tabs::

   .. code-tab:: c#

        if (!indicator.IsReady) return;

   .. code-tab:: py

        if not self.indicator.IsReady:
            return

|

Initializing Indicators
=======================

You can use two methods to prime technical indicators and get them ready to be used.

**Algorithm Warm-Up**

When we set an algorithm warm-up period, the engine pumps data in and automatically update all the indicators from before the start date (see :ref:`Setting Warm Up Period <algorithm-reference-historical-data>`). To ensure that all the indicators are ready after the algorithm warm-up period, you need to choose a lookback period that contains the required data.

.. tabs::

   .. code-tab:: c#

        public override void Initialize()
        {
            AddEquity(_symbol, Resolution.Hour);
            // define a 10-period daily RSI indicator with shortcut helper method
            _rsi = RSI(_symbol, 10,  MovingAverageType.Simple, Resolution.Daily);
            // set a warm-up period to initialize the indicator
            SetWarmUp(TimeSpan.FromDays(20));
            // Or warm up the indicator with bar count
            // SetWarmUp(10, Resolution.Daily)
        }

   .. code-tab:: py

        def Initialize(self):
            self.AddEquity("SPY", Resolution.Hour)
            # define a 10-period daily RSI indicator with shortcut helper method
            self.rsi = self.RSI("SPY", 10,  MovingAverageType.Simple, Resolution.Daily)
            # set a warm-up period to initialize the indicator
            self.SetWarmUp(timedelta(20))
            # Warm-up the indicator with bar count
            # self.SetWarmUp(10, Resolution.Daily)


Universe Selection does not support warm-up and, consequently, factors that depend on indicators are not updated.

**History Request Warm-Up**

Alternatively, we can request for historical data to update the indicator manually (see :ref:`Historical Data Requests <algorithm-reference-historical-data>`).

.. tabs::

   .. code-tab:: c#

        public override void Initialize()
        {
            AddEquity(_symbol, Resolution.Hour);
            // define a 10-period daily RSI indicator with shortcut helper method
            _rsi = RSI(_symbol, 10,  MovingAverageType.Simple, Resolution.Daily);
            // initialize the indicator with the daily history close price
            var history = History(_symbol, 10, Resolution.Daily);
            foreach (var bar in history) {
                _rsi.Update(bar.EndTime, bar.Close);
            }
        }

   .. code-tab:: py

        def Initialize(self):
            self.AddEquity("SPY", Resolution.Hour)
            # define a 10-period daily RSI indicator with shortcut helper method
            self.rsi = self.RSI("SPY", 10,  MovingAverageType.Simple, Resolution.Daily)
            # initialize the indicator with the daily history close price
            history = self.History(["SPY"], 10, Resolution.Daily)
                for time, row in history.loc["SPY"].iterrows():
                    self.rsi.Update(time, row["close"])

|

Basic Indicator Usage
=====================

QCAlgorithm provides a shortcut method for each indicator available. Each method creates an indicator object, hooks it up for automatic updates, and returns it to be used in your algorithm.

You can determine the specific requirements of the indicator from the reference table below.

The indicator resolution can be different from the resolution of your securities data. However, the resolution of the indicator should be equal to or higher than the resolution of your security. In most cases, this usage should be in the Initialize method. If you call this method several times, it will create a new indicator that is not ready to use.

To retrieve the numerical value of any indicator, you can use the ``Current.Value`` attribute of the indicator.

.. tabs::

   .. code-tab:: c#

        public override void Initialize()
        {
            AddEquity(_symbol, Resolution.Hour);
            // define a 10-period daily RSI indicator with shortcut helper method
            _rsi = RSI(_symbol, 10,  MovingAverageType.Simple, Resolution.Daily);
        }

        public override void OnData(Slice data)
        {
            // check if this algorithm is still warming up
            if(!_rsi.IsReady) return;

            // Once ready, get the current RSI value
            var rsiValue = _rsi;
            // get the current average gain of rsi
            var averageGain = _rsi.AverageGain;
            // get the current average loss of rsi
            var averageLoss = _rsi.AverageLoss;
        }

   .. code-tab:: py

        def Initialize(self):
            # request the hourly equity data
            self.AddEquity("SPY", Resolution.Hour)
            # define a 10-period daily RSI indicator with shortcut helper method
            self.rsi = self.RSI("SPY", 10,  MovingAverageType.Simple, Resolution.Daily)

        def OnData(self, data):
            # check if this algorithm is still warming up
            if self.rsi.IsReady:
                # get the current RSI value
                rsi_value = self.rsi.Current.Value
                # get the current average gain of rsi
                average_gain = self.rsi.AverageGain.Current.Value
                # get the current average loss of rsi
                average_loss = self.rsi.AverageLoss.Current.Value

|

Custom Period Indicators
========================

You can create an indicator object that is not bound to any automatic update and choose which data it should use. To use an indicator like this, you create an indicator with its constructor.

To see the LEAN indicator classes available and their constructor arguments, please look them up in the reference table below.

You can use two methods to update the indicator: automatic or manual.

**Automatic Update**

In this method, you will recreate the basic indicator usage: create an indicator with its constructor and register the indicator for automatic updates with the ``RegisterIndicator()`` method.

.. tabs::

   .. code-tab:: c#

        // request the daily equity data
        AddEquity("SPY", Resolution.Daily);
        // define a 10-period RSI indicator with indicator constructor
        _rsi = new RelativeStrengthIndex(10, MovingAverageType.Simple);
        // register the daily data of "SPY" to automatically update the indicator
        RegisterIndicator("SPY", _rsi, Resolution.Daily);

   .. code-tab:: py

        # request the daily equity data
        self.AddEquity("SPY", Resolution.Daily)
        # define a 10-period RSI indicator with indicator constructor
        self.rsi = RelativeStrengthIndex(10, MovingAverageType.Simple)
        # register the daily data of "SPY" to automatically update the indicator
        self.RegisterIndicator("SPY", self.rsi, Resolution.Daily)

Other than the available resolutions, you can also update the indicator with the consolidator. For details about data consolidator, please see :ref:`Consolidating Data <algorithm-reference-consolidating-data>`.

.. tabs::

   .. code-tab:: c#

        // request the equity data in minute resolution
        AddEquity(_symbol, Resolution.Hour);
        // define a 10-period RSI indicator with indicator constructor
        _rsi = new RelativeStrengthIndex(10, MovingAverageType.Simple);
        // create the 30-minutes data consolidator
        var thirtyMinuteConsolidator = new TradeBarConsolidator(TimeSpan.FromMinutes(30));
        SubscriptionManager.AddConsolidator("SPY", thirtyMinuteConsolidator);
        // register the 30-minute consolidated bar data to automatically update the indicator
        RegisterIndicator("SPY", _rsi,thirtyMinuteConsolidator);

   .. code-tab:: py

        # request the equity data in minute resolution
        self.AddEquity("SPY", Resolution.Minute)
        # define a 10-period RSI indicator with indicator constructor
        self.rsi = RelativeStrengthIndex(10, MovingAverageType.Simple)
        # create the 30-minutes data consolidator
        thirtyMinuteConsolidator = TradeBarConsolidator(timedelta(minutes=30))
        self.SubscriptionManager.AddConsolidator("SPY", thirtyMinuteConsolidator)
        # register the 30-minute consolidated bar data to automatically update the indicator
        self.RegisterIndicator("SPY", self.rsi, thirtyMinuteConsolidator)

**Manual Update**

Updating your indicator manually allows you to control which data is used and create indicators of other non-price fields. For instance, you can use the 3:30 pm price in your daily moving average instead of the after-market closing price, or you may want to use the maximum temperature of the past 10 cloudy days.

The indicator objects have the ``Update()`` method that updates the state of an indicator with the given value. Depending on the different types of indicators, this value can be the time/decimal pair, a trade bar, a quote bar, or a custom data bar.

With this method, the indicator will only be ready after the ``Update()`` method has been used to pump enough data. For example, a 10-period daily moving average needs to receive ten daily data points through the ``Update()`` method.

.. tabs::

   .. code-tab:: c#

        public override void Initialize() {
            AddEquity(_symbol, Resolution.Daily);
            _rsi = new RelativeStrengthIndex(10, MovingAverageType.Simple);
        }

        public override void OnData(Slice data) {
            // update the indicator value with the new input close price every day
            if (data.Bars.ContainsKey(_symbol)) {
                _rsi.Update(data[_symbol].EndTime, data[_symbol].Close);
            }
            // check if the indicator is ready
            if (_rsi.IsReady) {
            // get the current RSI value
                var rsiValue = _rsi;
            }
        }

   .. code-tab:: py

        def Initialize(self):
            self.AddEquity("SPY", Resolution.Daily)
            self.rsi = RelativeStrengthIndex(10, MovingAverageType.Simple)

        def OnData(self, data):
            # update the indicator value with the new input close price every day
            if data.Bars.ContainsKey("SPY"):
                self.rsi.Update(data["SPY"].EndTime, data["SPY"].Close)
            # check if the indicator is ready
            if self.rsi.IsReady:
                # get the current RSI value
                rsi_value = self.rsi.Current.Value

In both cases, we recommend using historical data to warm up your indicator as demonstrated in Initializing Indicators.

|

Updating Indicators with Custom Values
======================================

The data point indicators use only a single price data in their calculations. By default, those indicators use the closing price. For equity, that price is the trade bar closing price. For other asset classes with quote bar data (bid/ask price), those indicators are calculated with the mid-price of the bid closing price and the ask closing price.

If you want to create an indicator with the other fields like ``Open``, ``High``, ``Low``, or ``Close``, you can specify the selector argument in the indicator helper method with the available fields.

.. tabs::

   .. code-tab:: c#

        // define a 10-period daily RSI indicator with shortcut helper method
        // select the Open price to update the indicator
        _rsi = RSI("SPY", 10,  MovingAverageType.Simple, Resolution.Daily, Field.Open);

   .. code-tab:: py

        # define a 10-period daily RSI indicator with shortcut helper method
        # select the Open price to update the indicator
        self.rsi = self.RSI("SPY", 10,  MovingAverageType.Simple, Resolution.Daily, Field.Open)

You can also apply ``RegisterIndicator`` to register the price data with the specified field.

.. tabs::

   .. code-tab:: c#

        // define a 10-period RSI with indicator constructor
        _rsi = new RelativeStrengthIndex(10, MovingAverageType.Simple);
        // register the daily High price data to automatically update the indicator
        RegisterIndicator("SPY", _rsi, Resolution.Daily, Field.High);

   .. code-tab:: py

        # define a 10-period RSI with indicator constructor
        self.rsi = RelativeStrengthIndex(10, MovingAverageType.Simple)
        # register the daily High price data to automatically update the indicator
        self.RegisterIndicator("SPY", self.rsi, Resolution.Daily, Field.High)

.. code-block::

|

.. _algorithm-reference-indicators-reference-table:

Reference Table
===============

.. list-table::
   :widths: 25 50
   :header-rows: 1

   * - Indicators
     - Usage

   * - ``AccelerationBands``
     - Creates a new Acceleration Bands indicator.
       .. code-block::

          var abands = ABANDS(Symbol symbol, int period, decimal width = 4, MovingAverageType movingAverageType = null, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.TradeBar] selector = null)

   * - ``AccumulationDistribution``
     - Creates a new AccumulationDistribution indicator.
       .. code-block::

          var ad = AD(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.TradeBar] selector = null)

   * - ``AccumulationDistributionOscillator``
     - Creates a new AccumulationDistributionOscillator indicator.
       .. code-block::

          var adosc = ADOSC(Symbol symbol, int fastPeriod, int slowPeriod, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.TradeBar] selector = null)

   * - ``AverageDirectionalIndex``
     - Creates a new Average Directional Index indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var adx = ADX(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``AverageDirectionalMovementIndexRating``
     - Creates a new AverageDirectionalMovementIndexRating indicator.
       .. code-block::

          var adxr = ADXR(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``ArnaudLegouxMovingAverage``
     - Creates a new ArnaudLegouxMovingAverage indicator.
       .. code-block::

          var alma = ALMA(Symbol symbol, int period, int sigma = 6, decimal offset = 0.85, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``AbsolutePriceOscillator``
     - Creates a new AbsolutePriceOscillator indicator.
       .. code-block::

          var apo = APO(Symbol symbol, int fastPeriod, int slowPeriod, MovingAverageType movingAverageType, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``AroonOscillator``
     - Creates a new AroonOscillator indicator which will compute the AroonUp and AroonDown (as well as the delta)
       .. code-block::

          var aroon = AROON(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``AverageTrueRange``
     - Creates a new AverageTrueRange indicator for the symbol. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var atr = ATR(Symbol symbol, int period, MovingAverageType type = null, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``BollingerBands``
     - Creates a new BollingerBands indicator which will compute the MiddleBand, UpperBand, LowerBand, and StandardDeviation
       .. code-block::

          var bb = BB(Symbol symbol, int period, decimal k, MovingAverageType movingAverageType = null, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``BalanceOfPower``
     - Creates a new Balance Of Power indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var bop = BOP(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``CoppockCurve``
     - Initializes a new instance of the CoppockCurve" indicator
       .. code-block::

          var cc = CC(Symbol symbol, int shortRocPeriod = 11, int longRocPeriod = 14, int lwmaPeriod = 10, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``CommodityChannelIndex``
     - Creates a new CommodityChannelIndex indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var cci = CCI(Symbol symbol, int period, MovingAverageType movingAverageType = null, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``ChandeMomentumOscillator``
     - Creates a new ChandeMomentumOscillator indicator.
       .. code-block::

          var cmo = CMO(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``DonchianChannel``
     - Creates a new Donchian Channel indicator which will compute the Upper Band and Lower Band. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var dch = DCH(Symbol symbol, int upperPeriod, int lowerPeriod, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``DoubleExponentialMovingAverage``
     - Creates a new DoubleExponentialMovingAverage indicator.
       .. code-block::

          var dema = DEMA(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``DetrendedPriceOscillator``
     - Creates a new DetrendedPriceOscillator" indicator.
       .. code-block::

          var dpo = DPO(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``ExponentialMovingAverage``
     - Creates an ExponentialMovingAverage indicator for the symbol. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var ema = EMA(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``FractalAdaptiveMovingAverage``
     - Creates an FractalAdaptiveMovingAverage (FRAMA) indicator for the symbol. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var frama = FRAMA(Symbol symbol, int period, int longPeriod = 198, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``HullMovingAverage``
     - Creates a new HullMovingAverage indicator. The Hull moving average is a series of nested weighted moving averages, is fast and smooth.
       .. code-block::

          var hma = HMA(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``IchimokuKinkoHyo``
     - Creates a new IchimokuKinkoHyo indicator for the symbol. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var ichimoku = ICHIMOKU(Symbol symbol, int tenkanPeriod, int kijunPeriod, int senkouAPeriod, int senkouBPeriod, int senkouADelayPeriod, int senkouBDelayPeriod, Resolution resolution = null)

   * - ``KaufmanAdaptiveMovingAverage``
     - Creates a new KaufmanAdaptiveMovingAverage indicator.
       .. code-block::

          var kama = KAMA(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``KeltnerChannels``
     - Creates a new Keltner Channels indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var kch = KCH(Symbol symbol, int period, decimal k, MovingAverageType movingAverageType = null, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``LogReturn``
     - Creates and registers a new Least Squares Moving Average instance.
       .. code-block::

          var lsma = LSMA(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``LeastSquaresMovingAverage``
     - Creates a new Acceleration Bands indicator.
       .. code-block::

          var abands = ABANDS(Symbol symbol, int period, decimal width = 4, MovingAverageType movingAverageType = null, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.TradeBar] selector = null)

   * - ``LinearWeightedMovingAverage``
     - Creates a new LinearWeightedMovingAverage indicator. This indicator will linearly distribute the weights across the periods.
       .. code-block::

          var lwma = LWMA(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``MovingAverageConvergenceDivergence``
     - Creates a MACD indicator for the symbol. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var macd = MACD(Symbol symbol, int fastPeriod, int slowPeriod, int signalPeriod, MovingAverageType type = 1, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``MeanAbsoluteDeviation``
     - Creates a new MeanAbsoluteDeviation indicator.
       .. code-block::

          var mad = MAD(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``MassIndex``
     - Creates a new Mass Index indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var mass = MASS(Symbol symbol, int emaPeriod = 9, int sumPeriod = 25, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.TradeBar] selector = null)

   * - ``Maximum``
     - Creates a new Maximum indicator to compute the maximum value
       .. code-block::

          var max = MAX(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``MoneyFlowIndex``
     - Creates a new MoneyFlowIndex indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var mfi = MFI(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.TradeBar] selector = null)

   * - ``MidPoint``
     - Creates a new MidPoint indicator.
       .. code-block::

          var midpoint = MIDPOINT(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``MidPrice``
     - Creates a new MidPrice indicator.
       .. code-block::

          var midprice = MIDPRICE(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``Minimum``
     - Creates a new Minimum indicator to compute the minimum value
       .. code-block::

          var min = MIN(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``Momentum``
     - Creates a new Momentum indicator. This will compute the absolute n-period change in the security. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var mom = MOM(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``MomersionIndicator``
     - Creates a new Momersion indicator.
       .. code-block::

          var momersion = MOMERSION(Symbol symbol, int minPeriod, int fullPeriod, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``MomentumPercent``
     - Creates a new MomentumPercent indicator. This will compute the n-period percent change in the security. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var momp = MOMP(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``NormalizedAverageTrueRange``
     - Creates a new NormalizedAverageTrueRange indicator.
       .. code-block::

          var natr = NATR(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``OnBalanceVolume``
     - Creates a new On Balance Volume indicator. This will compute the cumulative total volume based on whether the close price being higher or lower than the previous period. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var obv = OBV(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.TradeBar] selector = null)

   * - ``PercentagePriceOscillator``
     - Creates a new PercentagePriceOscillator indicator.
       .. code-block::

          var ppo = PPO(Symbol symbol, int fastPeriod, int slowPeriod, MovingAverageType movingAverageType, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``ParabolicStopAndReverse``
     - Creates a new Parabolic SAR indicator
       .. code-block::

          var psar = PSAR(Symbol symbol, decimal afStart = 0.02, decimal afIncrement = 0.02, decimal afMax = 0.2, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``RegressionChannel``
     - Creates a new RegressionChannel indicator which will compute the LinearRegression, UpperChannel and LowerChannel lines, the intercept and slope
       .. code-block::

          var rc = RC(Symbol symbol, int period, decimal k, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``RateOfChange``
     - Creates a new RateOfChange indicator. This will compute the n-period rate of change in the security. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var roc = ROC(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``RateOfChangePercent``
     - Creates a new RateOfChangePercent indicator. This will compute the n-period percentage rate of change in the security. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var rocp = ROCP(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``RateOfChangeRatio``
     - Creates a new RateOfChangeRatio indicator.
       .. code-block::

          var rocr = ROCR(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``AccelerationBands``
     - Creates a new Acceleration Bands indicator.
       .. code-block::

          var abands = ABANDS(Symbol symbol, int period, decimal width = 4, MovingAverageType movingAverageType = null, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.TradeBar] selector = null)

   * - ``RelativeStrengthIndex``
     - Creates a new RelativeStrengthIndex indicator. This will produce an oscillator that ranges from 0 to 100 based on the ratio of average gains to average losses over the specified period.
       .. code-block::

          var rsi = RSI(Symbol symbol, int period, MovingAverageType movingAverageType = 2, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``SimpleMovingAverage``
     - Creates an SimpleMovingAverage indicator for the symbol. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var sma = SMA(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``StandardDeviation``
     - Creates a new StandardDeviation indicator. This will return the population standard deviation of samples over the specified period.
       .. code-block::

          var std = STD(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``Stochastic``
     - Creates a new Stochastic indicator.
       .. code-block::

          var sto = STO(Symbol symbol, int period, int kPeriod, int dPeriod, Resolution resolution = null)

   * - ``Sum``
     - Creates a new Sum indicator.
       .. code-block::

          var sum = SUM(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``SwissArmyKnife``
     - Creates Swiss Army Knife transformation for the symbol. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var swiss = SWISS(Symbol symbol, int period, Double delta, SwissArmyKnifeTool tool, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``T3MovingAverage``
     - Creates a new T3MovingAverage indicator.
       .. code-block::

          var t3 = T3(Symbol symbol, int period, decimal volumeFactor = 0.7, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``TripleExponentialMovingAverage``
     - Creates a new TripleExponentialMovingAverage indicator.
       .. code-block::

          var tema = TEMA(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``TrueRange``
     - Creates a new TrueRange indicator.
       .. code-block::

          var tr = TR(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``TriangularMovingAverage``
     - Creates a new TriangularMovingAverage indicator.
       .. code-block::

          var trima = TRIMA(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``Trix``
     - Creates a new Trix indicator.
       .. code-block::

          var trix = TRIX(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``UltimateOscillator``
     - Creates a new UltimateOscillator indicator.
       .. code-block::

          var ultosc = ULTOSC(Symbol symbol, int period1, int period2, int period3, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``Variance``
     - Creates a new Variance indicator. This will return the population variance of samples over the specified period.
       .. code-block::

          var var = VAR(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

   * - ``VolumeWeightedAveragePriceIndicator``
     - Creates an VolumeWeightedAveragePrice (VWAP) indicator for the symbol. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var vwap = VWAP(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.TradeBar] selector = null)

   * - ``IntradayVwap``
     - Creates an VolumeWeightedAveragePrice (VWAP) indicator for the symbol. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var vwap = VWAP(Symbol symbol)

   * - ``WilliamsPercentR``
     - Creates a new Williams %R indicator. This will compute the percentage change of the current closing price in relation to the high and low of the past N periods. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var wilr = WILR(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``WilderMovingAverage``
     - Creates a WilderMovingAverage indicator for the symbol. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var wwma = WWMA(Symbol symbol, int period, Resolution resolution = null, Func`2[Data.IBaseData,Decimal] selector = null)

|

.. list-table::
   :widths: 25 50
   :header-rows: 1

   * - Candlestick Patterns
     - Usage

   * - ``TwoCrows``
     - Creates a new CandlestickPatterns.TwoCrows" pattern indicator. The indicator will be automatically updated on the given resolution.


       .. code-block::

          var twocrows = CandlestickPatterns.TwoCrows(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``ThreeBlackCrows``
     - Creates a new CandlestickPatterns.ThreeBlackCrows" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var threeblackcrows = CandlestickPatterns.ThreeBlackCrows(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``ThreeInside``
     - Creates a new CandlestickPatterns.ThreeInside" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var threeinside = CandlestickPatterns.ThreeInside(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``ThreeLineStrike``
     - Creates a new CandlestickPatterns.ThreeLineStrike" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var threelinestrike = CandlestickPatterns.ThreeLineStrike(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``ThreeOutside``
     - Creates a new CandlestickPatterns.ThreeOutside" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

         var threeoutside = CandlestickPatterns.ThreeOutside(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``ThreeStarsInSouth``
     - Creates a new CandlestickPatterns.ThreeStarsInSouth" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var threestarsinsouth = CandlestickPatterns.ThreeStarsInSouth(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``ThreeWhiteSoldiers``
     - Creates a new CandlestickPatterns.ThreeWhiteSoldiers" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var threewhitesoldiers = CandlestickPatterns.ThreeWhiteSoldiers(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``AbandonedBaby``
     - Creates a new CandlestickPatterns.AbandonedBaby" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var abandonedbaby = CandlestickPatterns.AbandonedBaby(Symbol symbol, decimal penetration = 0.3, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``AdvanceBlock``
     - Creates a new CandlestickPatterns.AdvanceBlock" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var advanceblock = CandlestickPatterns.AdvanceBlock(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``BeltHold``
     - Creates a new CandlestickPatterns.BeltHold" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var belthold = CandlestickPatterns.BeltHold(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``Breakaway``
     - Creates a new CandlestickPatterns.Breakaway" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var breakaway = CandlestickPatterns.Breakaway(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``ClosingMarubozu``
     - CCreates a new CandlestickPatterns.ClosingMarubozu" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var closingmarubozu = CandlestickPatterns.ClosingMarubozu(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``ConcealedBabySwallow``
     - Creates a new CandlestickPatterns.ConcealedBabySwallow" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var concealedbabyswallow = CandlestickPatterns.ConcealedBabySwallow(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``Counterattack``
     - Creates a new CandlestickPatterns.Counterattack" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var counterattack = CandlestickPatterns.Counterattack(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``DarkCloudCover``
     - Creates a new CandlestickPatterns.DarkCloudCover" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var darkcloudcover = CandlestickPatterns.DarkCloudCover(Symbol symbol, decimal penetration = 0.5, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``Doji``
     - Creates a new CandlestickPatterns.Doji" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var doji = CandlestickPatterns.Doji(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``DojiStar``
     - Creates a new CandlestickPatterns.DojiStar" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var dojistar = CandlestickPatterns.DojiStar(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``DragonflyDoji``
     - Creates a new CandlestickPatterns.DragonflyDoji" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var dragonflydoji = CandlestickPatterns.DragonflyDoji(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``Engulfing``
     - Creates a new CandlestickPatterns.Engulfing" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var engulfing = CandlestickPatterns.Engulfing(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``EveningDojiStar``
     - Creates a new CandlestickPatterns.EveningDojiStar" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var eveningdojistar = CandlestickPatterns.EveningDojiStar(Symbol symbol, decimal penetration = 0.3, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``EveningStar``
     - Creates a new CandlestickPatterns.EveningStar" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var eveningstar = CandlestickPatterns.EveningStar(Symbol symbol, decimal penetration = 0.3, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``GapSideBySideWhite``
     - Creates a new CandlestickPatterns.GapSideBySideWhite" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var gapsidebysidewhite = CandlestickPatterns.GapSideBySideWhite(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``GravestoneDoji``
     - Creates a new CandlestickPatterns.GravestoneDoji" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var gravestonedoji = CandlestickPatterns.GravestoneDoji(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``Hammer``
     - Creates a new CandlestickPatterns.Hammer" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var hammer = CandlestickPatterns.Hammer(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``HangingMan``
     - Creates a new CandlestickPatterns.HangingMan" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var hangingman = CandlestickPatterns.HangingMan(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``Harami``
     - Creates a new CandlestickPatterns.Harami" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var harami = CandlestickPatterns.Harami(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``HaramiCross``
     - Creates a new CandlestickPatterns.HaramiCross" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var haramicross = CandlestickPatterns.HaramiCross(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``HighWaveCandle``
     - Creates a new CandlestickPatterns.HighWaveCandle" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var highwavecandle = CandlestickPatterns.HighWaveCandle(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``Hikkake``
     - Creates a new CandlestickPatterns.Hikkake" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var hikkake = CandlestickPatterns.Hikkake(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``HikkakeModified``
     - Creates a new CandlestickPatterns.HikkakeModified" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var hikkakemodified = CandlestickPatterns.HikkakeModified(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``HomingPigeon``
     - Creates a new CandlestickPatterns.HomingPigeon" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var homingpigeon = CandlestickPatterns.HomingPigeon(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``IdenticalThreeCrows``
     - Creates a new CandlestickPatterns.IdenticalThreeCrows" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var identicalthreecrows = CandlestickPatterns.IdenticalThreeCrows(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``InNeck``
     - Creates a new CandlestickPatterns.InNeck" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var inneck = CandlestickPatterns.InNeck(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``InvertedHammer``
     - Creates a new CandlestickPatterns.InvertedHammer" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var invertedhammer = CandlestickPatterns.InvertedHammer(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``Kicking``
     - Creates a new CandlestickPatterns.Kicking" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var kicking = CandlestickPatterns.Kicking(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``KickingByLength``
     - Creates a new CandlestickPatterns.KickingByLength" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var kickingbylength = CandlestickPatterns.KickingByLength(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``LadderBottom``
     - Creates a new CandlestickPatterns.LadderBottom" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var ladderbottom = CandlestickPatterns.LadderBottom(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``LongLeggedDoji``
     - Creates a new CandlestickPatterns.LongLeggedDoji" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var longleggeddoji = CandlestickPatterns.LongLeggedDoji(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``LongLineCandle``
     - Creates a new CandlestickPatterns.LongLineCandle" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var longlinecandle = CandlestickPatterns.LongLineCandle(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``Marubozu``
     - Creates a new CandlestickPatterns.Marubozu" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var marubozu = CandlestickPatterns.Marubozu(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``MatchingLow``
     - Creates a new CandlestickPatterns.MatchingLow" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var matchinglow = CandlestickPatterns.MatchingLow(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``MatHold``
     - Creates a new CandlestickPatterns.MatHold" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var mathold = CandlestickPatterns.MatHold(Symbol symbol, decimal penetration = 0.5, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``MorningDojiStar``
     - Creates a new CandlestickPatterns.MorningDojiStar" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var morningdojistar = CandlestickPatterns.MorningDojiStar(Symbol symbol, decimal penetration = 0.3, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``MorningStar``
     - Creates a new CandlestickPatterns.MorningStar" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var morningstar = CandlestickPatterns.MorningStar(Symbol symbol, decimal penetration = 0.3, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``OnNeck``
     - Creates a new CandlestickPatterns.OnNeck" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var onneck = CandlestickPatterns.OnNeck(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``Piercing``
     - Creates a new CandlestickPatterns.Piercing" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var piercing = CandlestickPatterns.Piercing(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``RickshawMan``
     - Creates a new CandlestickPatterns.RickshawMan" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var rickshawman = CandlestickPatterns.RickshawMan(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``RiseFallThreeMethods``
     - Creates a new CandlestickPatterns.RiseFallThreeMethods" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var risefallthreemethods = CandlestickPatterns.RiseFallThreeMethods(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``SeparatingLines``
     - Creates a new CandlestickPatterns.SeparatingLines" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var separatinglines = CandlestickPatterns.SeparatingLines(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``ShootingStar``
     - Creates a new CandlestickPatterns.ShootingStar" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var shootingstar = CandlestickPatterns.ShootingStar(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``ShortLineCandle``
     - Creates a new CandlestickPatterns.ShortLineCandle" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var shortlinecandle = CandlestickPatterns.ShortLineCandle(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``SpinningTop``
     - Creates a new CandlestickPatterns.SpinningTop" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var spinningtop = CandlestickPatterns.SpinningTop(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``StalledPattern``
     - Creates a new CandlestickPatterns.StalledPattern" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var stalledpattern = CandlestickPatterns.StalledPattern(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``StickSandwich``
     - Creates a new CandlestickPatterns.StickSandwich" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var sticksandwich = CandlestickPatterns.StickSandwich(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``Takuri``
     - Creates a new CandlestickPatterns.Takuri" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var takuri = CandlestickPatterns.Takuri(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``TasukiGap``
     - Creates a new CandlestickPatterns.TasukiGap" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var tasukigap = CandlestickPatterns.TasukiGap(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``Thrusting``
     - Creates a new CandlestickPatterns.Thrusting" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var thrusting = CandlestickPatterns.Thrusting(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``Tristar``
     - Creates a new CandlestickPatterns.Tristar" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var tristar = CandlestickPatterns.Tristar(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``UniqueThreeRiver``
     - Creates a new CandlestickPatterns.UniqueThreeRiver" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var uniquethreeriver = CandlestickPatterns.UniqueThreeRiver(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``UpsideGapTwoCrows``
     - Creates a new CandlestickPatterns.UpsideGapTwoCrows" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var upsidegaptwocrows = CandlestickPatterns.UpsideGapTwoCrows(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

   * - ``UpDownGapThreeMethods``
     - Creates a new CandlestickPatterns.UpDownGapThreeMethods" pattern indicator. The indicator will be automatically updated on the given resolution.
       .. code-block::

          var updowngapthreemethods = CandlestickPatterns.UpDownGapThreeMethods(Symbol symbol, Resolution resolution = null, Func`2[Data.IBaseData,Data.Market.IBaseDataBar] selector = null)

.. tabs::

   .. code-tab:: c#

        // 1. Using basic indicator at the same resolution as source security:
        // TIP -> You can use string "IBM" or the security.Symbol object
        var ema = EMA("IBM", 14);
        var rsi = RSI("IBM", 14);

        //2. Using indicator at different (higher) resolution to the source security:
        var emaDaily = EMA("IBM", 14, Resolution.Daily);

        //3. Indicator of a different property (default is close of bar/data):
        // TIP -> You can use helper methods Field.Open, Field.High etc on the indicator selector:
        var emaDailyHigh = EMA("IBM", 14, Resolution.Daily, point => ((TradeBar) point).High);

        //4. Using the indicators:
        //4.1  Setup in initialize:
        _emaFast = EMA("IBM", 14);
        _emaSlow = EMA("IBM", 28);

        //4.2 Use in OnData:
        if (_emaSlow.IsReady && _emaFast.IsReady) {
           if (_emaFast > _emaSlow) {
               //Long.
           } else if (_emaFast < _emaSlow) {
               //Short.
           }
        }

        //NOTE. Some indicators require tradebars (ATR, AROON) so your selector must return a TradeBar object for those indicators.

   .. code-tab:: py

        # 1. Using basic indicator at the same resolution as source security:
        self.ema = self.EMA("IBM", 14)
        self.rsi = self.RSI("IBM", 14)

        #2. Using indicator at different (higher) resolution to the source security:
        self.emaDaily = self.EMA("IBM", 14, Resolution.Daily)

        #3. Indicator of a different property (default is close of bar/data):
        self.emaDailyHigh = self.EMA("IBM", 14, Resolution.Daily, Field.High)


        #4. Using the indicators:
        #4.1  Setup in initialize: make sure you've asked for the data for the asset.
        self.AddEquity("IBM")
        self.emaFast = self.EMA("IBM", 14);
        self.emaSlow = self.EMA("IBM", 28);

        #4.2 Consume the indicators in OnData.
        if self.emaSlow.IsReady and self.emaFast.IsReady:
            if self.emaFast.Current.Value > self.emaSlow.Current.Value:
                self.Debug("Long")
            elif self.emaFast.Current.Value < self.emaSlow.Current.Value:
                self.Debug("Short")

|

.. _algorithm-reference-indicators-indicator-extensions:

Indicator Extensions
====================

Indicators are *composable* - meaning they can be *chained* together to create unique combinations much like lego blocks. We support several indicator extensions as outlined below:

.. list-table::
   :widths: 25 50
   :header-rows: 1

   * - Extensions
     - Example Usage

   * - .Plus()
     - ``emaSum = IndicatorExtensions.Plus(ema20, ema5)``
        Add a fixed value or indicator value to another indicator

   * - .Minus()
     - ``emaDelta = IndicatorExtensions.Minus(ema5, ema20)``
        Find the difference between two indicators

   * - .Times()
     - ``rsiSafe = IndicatorExtensions.Times(rsi, 0.95)``
        Multiply one indicator or constant value by another.

   * - .Over()
     - ``emaAverage = IndicatorExtensions.Over(IndicatorExtensions.Plus(ema10, ema5), 2)``
        Divide indicator chain by constant or indicator.

   * - .Of()
     - ``sma = SimpleMovingAverage("SPY", 14)``
       ``rsiAverage= IndicatorExtensions.Of(rsi, sma)``
       Feed an indicator output into input of another

   * - .SMA(int period)
     - ``rsiAvg = IndicatorExtensions.SMA(rsi, 10)``
        Of extension helper for SMA method.

   * - .EMA(int period)
     - ``rsiAvg = IndicatorExtensions.EMA(rsi, 10)``
        Of extension helper for EMA method.

   * - .MAX(int period)
     - ``rsiMax = IndicatorExtensions.MAX(rsi, 10)``
        Of extension helper for MAX method, get max in i-samples.

   * - .MIN(int period)
     - ``rsiMin = IndicatorExtensions.MIN(rsi, 10)``
        Of extension helper for MIN method, get min in i-samples.

.. tabs::

   .. code-tab:: c#

        public class IndicatorTests : QCAlgorithm
        {
            //Save off reference to indicator objects
            RelativeStrengthIndex _rsi;
            SimpleMovingAverage _rsiSMA;

            public override void Initialize()
            {
               //In addition to other initialize logic:
               _rsi = RSI("SPY", 14); // Creating a RSI
               _rsiSMA = _rsi.SMA(3); // Creating the SMA on the RSI
            }

            public override void OnData(Slice data)
            {
               Plot("RSI", _rsi, _rsiSMA);
            }
        }

   .. code-tab:: py

        class IndicatorTests(QCAlgorithm):
            def Initialize():
               # In addition to other initialize logic:
               self.rsi = self.RSI("SPY", 14)                     # Creating a RSI
               self.rsiSMA = IndicatorExtensions.SMA(self.rsi, 3) # Creating the SMA on the RSI
               self.PlotIndicator("RSI", self.rsi, self.rsiSMA)

|

Plotting Indicators
===================

We provide a helper method which aims to make plotting indicators simple. For further information on the charting API please see our :ref:`Charting <algorithm-reference-charting>` section.

.. tabs::

   .. code-tab:: c#

        Plot(string chart, Indicator[] indicators)

   .. code-tab:: py

        self.Plot(string chart, Indicator[] indicators)

Note that not all indicators share the same base type(T) so may not work together as some indicators require points where others require TradeBars.

.. tabs::

   .. code-tab:: c#

        //Plot array of indicator objects; extending "Indicator" type.
        Plot("Indicators", sma, rsi);

        //Plot array of indicator objects; extending "TradeBarIndicator" type.
        Plot("Indicators", atr, aroon);

        //For complex plotting it might be easiest to simply plot your indicators individually.

   .. code-tab:: py

        #Plot array of indicator objects; extending "Indicator" type.
        self.Plot("Indicators", sma, rsi);

        #Plot array of indicator objects; extending "TradeBarIndicator" type.
        self.Plot("Indicators", atr, aroon);

        #Currently, there is a limit of 4 indicators for each Plot call
        #For complex plotting it might be easiest to simply plot your indicators individually.