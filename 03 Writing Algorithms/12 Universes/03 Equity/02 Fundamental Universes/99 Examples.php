<meta content="universes" name="tag">
<meta content="fundamental universes" name="tag">
<p>
 The following examples demonstrate some common practices for fundamental universes.
</p>
<h4>
 Example 1: 500 Stocks &gt;$10/Share and &gt;$10M in Daily Trading Volume
</h4>
<p>
 The following algorithm selects the 500 most liquid US Equities above $10/share and $10M in daily volume.
</p>
<div class="section-example-container testable">
 <pre class="csharp">public class LiquidNonPennyStocksUniverseAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);

        // Configure the universe to update at the start of each month. Most of the top 500 doesn't change very 
        // frequently.
        UniverseSettings.Schedule.On(DateRules.MonthStart());
        // Add a universe with custom selection rules for filtering.
        AddUniverse(fundamental =&gt; (from f in fundamental
            where f.Price &gt; 10 &amp;&amp; f.DollarVolume &gt; 10000000
            orderby f.DollarVolume descending
            select f.Symbol).Take(500));
    }
}</pre>
<pre class="python">class LiquidNonPennyStocksUniverseAlgorithm(QCAlgorithm):

    def initialize(self) -&gt; None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        
        # Configure the universe to update at the start of each month. Most of the top 500 doesn't change very 
        # frequently.
        self.universe_settings.schedule.on(self.date_rules.month_start())
        # Add a universe with custom selection rules for filtering.
        self.add_universe(
            lambda fundamental: [
                x.symbol for x in sorted(
                    [f for f in fundamental if f.price &gt; 10 and f.dollar_volume &gt; 10_000_000], 
                    key=lambda f: f.dollar_volume
                )[-500:]
            ]
        )</pre>
</div>
<h4>
 Example 2: 10 Stocks Above Their 200-Day EMA With &gt;$1B of Daily Trading Volume
</h4>
<p>
 Another common request is to filter the universe by a technical indicator, such as only picking stocks above their 200-day
 <a href="/docs/v2/writing-algorithms/indicators/supported-indicators/exponential-moving-average">
  Exponential Moving Average
 </a>
 (EMA). 
  The
 <code>
  Fundamental
 </code>
 object has daily price and volume information, so you can do any price-related analysis.
  The following algorithm defines a separate class to contain the indicator of each asset.
</p>
<div class="section-example-container testable">
 <pre class="csharp">public class UpTrendLiquidUniverseAlgorithm  : QCAlgorithm
{    
    private Dictionary&lt;Symbol, SelectionData&gt; _selectionDataBySymbol = new();
    private Universe _universe;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        Settings.SeedInitialPrices = true;
        // Add the custom universe.
        UniverseSettings.DataNormalizationMode = DataNormalizationMode.Raw;
        _universe = AddUniverse(SelectAssets);
        // Add a warm-up period to warm up the indicators.
        SetWarmUp(TimeSpan.FromDays(300));
    }

    private IEnumerable&lt;Symbol&gt; SelectAssets(IEnumerable&lt;Fundamental&gt; fundamentals)
    {
        // Update the indicator of all stocks in the universe dataset and
        // get the subset of stocks that have their indicator ready.
        var readyStocks = new List&lt;Fundamental&gt;();
        foreach (var f in fundamentals)
        {
            if (!_selectionDataBySymbol.TryGetValue(f.Symbol, out var sd))
            {
                sd = new SelectionData(this, f, 200);
                _selectionDataBySymbol[f.Symbol] = sd;
            }
            if (sd.Update(f))
            {
                readyStocks.Add(f);
            }
        }
        // As assests leave the Fundamental dataset, delete their SelectionData object.
        var activeStocks = fundamentals.Select(f => f.Symbol).ToHashSet();
        foreach (var symbol in _selectionDataBySymbol.Keys.Where(s => !activeStocks.Contains(s)).ToList())
        {
            _selectionDataBySymbol.Remove(symbol);
        }
        // During warm-up, keep the universe empty.
        if (IsWarmingUp)
        {
            return Enumerable.Empty&lt;Symbol&gt;();
        }
        // Select the Equities that are above their EMA and have a daily volume of $1B.
        // These assets are in an uptrend and are very liquid.
        return readyStocks
            .Select(f => _selectionDataBySymbol[f.Symbol])
            .Where(x => x.IsAboveEma && x.Volume > 1000000000)
            // Select the 10 most liquid Equities to avoid extra slippage.    
            .OrderBy(x => x.Volume)
            .TakeLast(10)
            .Select(x => x.Symbol);
    }
}

// Create a separate class to contain the EMA information of each asset.
public class SelectionData
{
    private QCAlgorithm _algorithm;
    private decimal _priceScaleFactor;
    private ExponentialMovingAverage _ema { get; }
    public Symbol Symbol;
    public bool IsAboveEma = false;
    public decimal Volume = 0;

    public SelectionData(QCAlgorithm algorithm, Fundamental f, int period)
    {
        _algorithm = algorithm;
        _priceScaleFactor = f.PriceScaleFactor;
        // Create an EMA indicator for trend estimation and filtering.
        Symbol = f.Symbol;
        _ema = new ExponentialMovingAverage(period);
    }

    // Update your variables and indicators with the latest data.
    public bool Update(Fundamental f)
    {
        // If there hasn't been a split or dividend since the last trading
        // day, just update the indicator like normal.
        if (f.PriceScaleFactor == _priceScaleFactor)
        {
            return _update(f.EndTime, f.Volume, f.Price);
        }
        // Otherwise, reset the indicator and warm it up with the new
        // adjusted history.
        _priceScaleFactor = f.PriceScaleFactor;
        _ema.Reset();
        var history = _algorithm.History&lt;TradeBar&gt;(
            Symbol,
            _ema.WarmUpPeriod,
            Resolution.Daily,
            dataNormalizationMode: DataNormalizationMode.ScaledRaw
        );
        foreach (var bar in history)
        {
            _update(bar.EndTime, bar.Volume, bar.Close);
        }
        return _ema.IsReady;
    }

    private bool _update(DateTime endTime, decimal volume, decimal price)
    {
        Volume = volume * price;
        if (_ema.Update(endTime, price))
        {
            IsAboveEma = price > _ema.Current.Value;
        }
        return _ema.IsReady;
    }
}</pre>
<pre class="python">class UpTrendLiquidUniverseAlgorithm(QCAlgorithm):
	
    _selection_data_by_symbol = {}

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.settings.seed_initial_prices = True
        # Add the custom universe.
        self.universe_settings.data_normalization_mode = DataNormalizationMode.RAW
        self._universe = self.add_universe(self._select_assets)
        # Add a warm-up period to warm up the indicators.
        self.set_warm_up(timedelta(300))
    
    def _select_assets(self, fundamentals: List[Fundamental]) -> List[Symbol]:
        # Update the indicator of all stocks in the universe dataset and
        # get the subset of stocks that have their indicator ready.
        ready_stocks = [
            f for f in fundamentals
            if self._selection_data_by_symbol.setdefault(f.symbol, SelectionData(self, f, 200)).update(f)
        ]
        # As assests leave the Fundamental dataset, delete their SelectionData object.
        for symbol in self._selection_data_by_symbol.keys() - {f.symbol for f in fundamentals}:
            del self._selection_data_by_symbol[symbol]
        # During warm-up, keep the universe empty.
        if self.is_warming_up:
            return []
        # Select the Equities that are above their EMA and have a daily volume of $1B.
        # These assets are in an uptrend and are very liquid.
        selected = [self._selection_data_by_symbol[f.symbol] for f in ready_stocks]
        selected = [x for x in selected if x.is_above_ema and x.volume > 1_000_000_000]          
        # Select the 10 most liquid Equities to avoid extra slippage.    
        return [x.symbol for x in sorted(selected, key=lambda x: x.volume)[-10:]]


# Create a separate class to contain the EMA information of each asset.
class SelectionData:

    def __init__(self, algorithm, f, period):
        self._algorithm = algorithm
        self._price_scale_factor = f.price_scale_factor
        # Create an EMA indicator for trend estimation and filtering.
        self.symbol = f.symbol
        self._ema = ExponentialMovingAverage(period)
        self.is_above_ema = False
        self.volume = 0

    # Update your variables and indicators with the latest data.
    def update(self, f):
        # If there hasn't been a split or dividend since the last trading
        # day, just update the indicator like normal.
        if f.price_scale_factor == self._price_scale_factor:
            return self._update(f.end_time, f.volume, f.price)
        # Otherwise, reset the indicator and warm it up with the new 
        # adjusted history.
        self._price_scale_factor = f.price_scale_factor
        self._ema.reset()
        history = self._algorithm.history[TradeBar](
            self.symbol, 
            self._ema.warm_up_period, 
            Resolution.DAILY, 
            data_normalization_mode=DataNormalizationMode.SCALED_RAW
        )
        for bar in history:
            self._update(bar.end_time, bar.volume, bar.close)
        return self._ema.is_ready
    
    def _update(self, end_time, volume, price):
        self.volume = volume * price 
        if self._ema.update(end_time, price):
            self.is_above_ema = price > self._ema.current.value
        return self._ema.is_ready</pre>
</div>
<p>
 In this example, the
 <code>
  SelectionData
 </code>
 class group variables for the universe selection and updates the indicator of each asset. 
	We highly recommend you follow this pattern to keep your algorithm tidy and bug free. 
	The following snippet shows an example implementation of the
 <code>
  SelectionData
 </code>
 class, but you can make this whatever you need to store your custom universe filters. 
	Note that the preceding
 <code>
  SelectionData
 </code>
 class uses a
 <a href="/docs/v2/writing-algorithms/indicators/manual-indicators">
  manual
 </a>
 EMA indicator instead of the
 <a href="/docs/v2/writing-algorithms/indicators/automatic-indicators">
  automatic version
 </a>
 . 
	For more information about universes that select assets based on indicators, see
 <a href="/docs/v2/writing-algorithms/indicators/indicator-universes">
  Indicator Universes
 </a>
 . 
	You need to use a
 <code>
  SelectionData
 </code>
 class instead of assigning the EMA to the
 <code>
  Fundamental
 </code>
 object because you can't create custom
 <span class="csharp">
  properties
 </span>
 <span class="python">
  attributes
 </span>
 on
 <code>
  Fundamental
 </code>
 objects like you can with
 <code>
  Security
 </code>
 objects.
</p>
<h4>
 Example 3: 10 Stocks Furthest Above their 10-day SMA of Volume
</h4>
<p>
 The process to get the 10-day
 <a href="/docs/v2/writing-algorithms/indicators/supported-indicators/simple-moving-average">
  Simple Moving Average
 </a>
 (SMA) stock volume is the same process as in Example 2. 
	First, define a
 <code>
  SelectionData
 </code>
 class that performs the averaging. 
	This class tracks the ratio of today's volume relative to historical volumes. 
	You can use this ratio to select assets that are above their 10-day SMA and sort the results by the Equities that have had the biggest jump since yesterday.
</p>
<div class="section-example-container testable">
 <pre class="csharp">public class HighRelativeVolumeUniverseAlgorithm  : QCAlgorithm
{    
    private Dictionary&lt;Symbol, SelectionData&gt; _selectionDataBySymbol = new();
    private Universe _universe;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        Settings.SeedInitialPrices = true;
        // Add the custom universe.
        UniverseSettings.DataNormalizationMode = DataNormalizationMode.Raw;
        _universe = AddUniverse(SelectAssets);
        // Add a warm-up period to warm up the indicators.
        SetWarmUp(TimeSpan.FromDays(30));
    }

    private IEnumerable&lt;Symbol&gt; SelectAssets(IEnumerable&lt;Fundamental&gt; fundamentals)
    {
        // Update the indicator of all stocks in the universe dataset and
        // get the subset of stocks that have their indicator ready.
        var readyStocks = new List&lt;Fundamental&gt;();
        foreach (var f in fundamentals)
        {
            if (!_selectionDataBySymbol.TryGetValue(f.Symbol, out var sd))
            {
                sd = new SelectionData(this, f, 10);
                _selectionDataBySymbol[f.Symbol] = sd;
            }
            if (sd.Update(f))
            {
                readyStocks.Add(f);
            }
        }
        // As assests leave the Fundamental dataset, delete their SelectionData object.
        var activeStocks = fundamentals.Select(f => f.Symbol).ToHashSet();
        foreach (var symbol in _selectionDataBySymbol.Keys.Where(s => !activeStocks.Contains(s)).ToList())
        {
            _selectionDataBySymbol.Remove(symbol);
        }
        // During warm-up, keep the universe empty.
        if (IsWarmingUp)
        {
            return Enumerable.Empty&lt;Symbol&gt;();
        }
        // Select the Equities with higher trading volume than their SMA, indicating higher capital flow.
        return readyStocks
            .Select(f => _selectionDataBySymbol[f.Symbol])
            .Where(x => x.VolumeRatio > 1)
            // Select the 10 Equities with the highest relative volume, since they have the highest capactity
            // for scalp-trading or intra-day movement. 
            .OrderBy(x => x.VolumeRatio)
            .TakeLast(10)
            .Select(x => x.Symbol);
    }
}

// Create a separate class to contain the SMA information of each asset.
public class SelectionData
{
    private QCAlgorithm _algorithm;
    private decimal _priceScaleFactor;
    private SimpleMovingAverage _sma { get; }
    public Symbol Symbol;
    public decimal VolumeRatio = 0;

    public SelectionData(QCAlgorithm algorithm, Fundamental f, int period)
    {
        _algorithm = algorithm;
        _priceScaleFactor = f.PriceScaleFactor;
        // Create an EMA indicator for trend estimation and filtering.
        Symbol = f.Symbol;
        _sma = new SimpleMovingAverage(period);
    }

    // Update your variables and indicators with the latest data.
    public bool Update(Fundamental f)
    {
        // If there hasn't been a split or dividend since the last trading
        // day, just update the indicator like normal.
        if (f.PriceScaleFactor == _priceScaleFactor)
        {
            return _update(f.EndTime, f.Volume);
        }
        // Otherwise, reset the indicator and warm it up with the new
        // adjusted history.
        _priceScaleFactor = f.PriceScaleFactor;
        _sma.Reset();
        var history = _algorithm.History&lt;TradeBar&gt;(
            Symbol,
            _sma.WarmUpPeriod,
            Resolution.Daily,
            dataNormalizationMode: DataNormalizationMode.ScaledRaw
        );
        foreach (var bar in history)
        {
            _update(bar.EndTime, bar.Volume);
        }
        return _sma.IsReady;
    }

    private bool _update(DateTime endTime, decimal volume)
    {
        // Update the SMA with today's data and calculate the relative volume position for filtering.
        var ready = _sma.Update(endTime, volume);
        VolumeRatio = _sma.Current.Value != 0m ? volume / _sma.Current.Value : -1m;
        return ready;
    }
}</pre>
<pre class="python">class HighRelativeVolumeUniverseAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.settings.seed_initial_prices = True
        # Add the custom universe.
        self._selection_data_by_symbol = {}
        self.universe_settings.data_normalization_mode = DataNormalizationMode.RAW
        self._universe = self.add_universe(self._select_assets)
        # Add a warm-up period to warm up the indicators.
        self.set_warm_up(timedelta(30))
    
    def _select_assets(self, fundamentals: List[Fundamental]) -> List[Symbol]:
        # Update the indicator of all stocks in the universe dataset and
        # get the subset of stocks that have their indicator ready.
        ready_stocks = [
            f for f in fundamentals
            if self._selection_data_by_symbol.setdefault(f.symbol, SelectionData(self, f, 10)).update(f)
        ]
        # As assests leave the Fundamental dataset, delete their SelectionData object.
        for symbol in self._selection_data_by_symbol.keys() - {f.symbol for f in fundamentals}:
            del self._selection_data_by_symbol[symbol]
        # During warm-up, keep the universe empty.
        if self.is_warming_up:
            return []
        # Select the Equities with higher trading volume than their SMA, indicating higher capital flow.
        selected = [self._selection_data_by_symbol[f.symbol] for f in ready_stocks]
        selected = [sd for sd in selected if sd.volume_ratio > 1]        
        # Select the 10 Equities with the highest relative volume, since they have the highest capactity
        # for scalp-trading or intra-day movement.
        return [x.symbol for x in sorted(selected, key=lambda x: x.volume_ratio)[-10:]]


# Create a separate class to contain the SMA information of each asset.
class SelectionData:

    def __init__(self, algorithm, f, period):
        self._algorithm = algorithm
        self._price_scale_factor = f.price_scale_factor
        # Create an SMA of volume to track the popularity of the stock.
        self.symbol = f.symbol
        self._sma = SimpleMovingAverage(period)

    # Update your variables and indicators with the latest data.
    def update(self, f):
        # If there hasn't been a split or dividend since the last trading
        # day, just update the indicator like normal.
        if f.price_scale_factor == self._price_scale_factor:
            return self._update(f.end_time, f.volume)
        # Otherwise, reset the indicator and warm it up with the new 
        # adjusted history.
        self._price_scale_factor = f.price_scale_factor
        self._sma.reset()
        history = self._algorithm.history[TradeBar](
            self.symbol, 
            self._sma.warm_up_period, 
            Resolution.DAILY, 
            data_normalization_mode=DataNormalizationMode.SCALED_RAW
        )
        for bar in history:
            self._update(bar.end_time, bar.volume)
        return self._sma.is_ready
    
    def _update(self, end_time, volume):
        # Update the SMA with today's data and calculate the relative volume position for filtering.
        if self._sma.update(end_time, volume):
            self.volume_ratio = volume / self._sma.current.value if self._sma.current.value != 0 else -1
        return self._sma.is_ready</pre>
</div>
<h4>
 Example 4: 10 "Fastest Moving" Stocks With a 50-Day EMA &gt; 200 Day EMA
</h4>
<p>
 You can construct complex universe filters with the
 <code>
  SelectionData
 </code>
 helper class pattern. 
	To view a full example of this algorithm, see the
 <a class="csharp" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/EmaCrossUniverseSelectionAlgorithm.cs" rel="nofollow" target="_blank">
  EmaCrossUniverseSelectionAlgorithm
 </a>
 <a class="python" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/EmaCrossUniverseSelectionAlgorithm.py" rel="nofollow" target="_blank">
  EmaCrossUniverseSelectionAlgorithm
 </a>
 in the LEAN GitHub repository or take the
 <a href="/learning/task/153/">
  related Boot Camp lesson
 </a>
 .
</p>
<h4>
 Example 5: Piotroski F-Score
</h4>
<p>
 To view this example, see the
 <a href="/research/15728/piotroski-f-score-investing/p1">
  Piotroski F-Score Investing
 </a>
 Research post.
</p>

<h4>Example 6: Stocks Far Above Their SMA</h4>
<? include(DOCS_RESOURCES."/examples/reset-universe-indicators-on-corporate-actions.php"); ?>

<h4>
 Other Examples
</h4>
<p>
 For more examples, see the following algorithms:
</p>
<div class="example-fieldset">
 <div class="example-legend">
  Demonstration Algorithms
 </div>
 <a class="python example-algorithm-link" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.Python/FundamentalUniverseSelectionAlgorithm.py" target="_BLANK">
  FundamentalUniverseSelectionAlgorithm.py
  <span class="badge-python pull-right">
   Python
  </span>
 </a>
 <a class="csharp example-algorithm-link" href="https://github.com/QuantConnect/Lean/blob/master/Algorithm.CSharp/FundamentalUniverseSelectionRegressionAlgorithm.cs" target="_BLANK">
  FundamentalUniverseSelectionAlgorithm.cs
  <span class="badge badge-sm badge-csharp pull-right">
   C#
  </span>
 </a>
</div>
