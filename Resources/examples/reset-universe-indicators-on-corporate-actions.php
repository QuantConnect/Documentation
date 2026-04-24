<p>
    The following example shows how to select a universe of US Equities based on their price and SMA indicator.
    The <code>SelectionData</code> class keeps track of the SMA indicator for each stock in the universe dataset.
    When a split or dividend occurs for a stock, the data in its indicator becomes invalid because it doesn't account for the price adjustments that the split or dividend causes.
    The <code>SelectionData</code> class resets and warms up the indicator with the <code class='csharp'>ScaledRaw</code><code class='python'>SCALED_RAW</code> <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#11-Data-Normalization'>data normalization mode</a>, which gives you accurate indicator values to use in your universe selection after each corporate action.
</p>
<div class="section-example-container testable">
	<pre class="python">class EquityIndicatorUniverseSelectionAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        self.settings.seed_initial_prices = True
        # Add a universe of US Equities based on an indicator.
        self._selection_data_by_symbol = {}
        self._universe = self.add_universe(self._select_assets)
        # Add a warm-up period to warm up the indicators in the universe selection.
        self.set_warm_up(timedelta(60))

    def _select_assets(self, fundamentals):
        # Update the indicator of all stocks in the universe dataset and
        # get the subset of stocks that have their indicator ready.
        ready_stocks = [
            f for f in fundamentals
            if self._selection_data_by_symbol.setdefault(f.symbol, SelectionData(self, f)).update(f)
        ]
        # As assests leave the Fundamental dataset, delete their SelectionData object.
        for symbol in self._selection_data_by_symbol.keys() - {f.symbol for f in fundamentals}:
            del self._selection_data_by_symbol[symbol]
        # During warm-up, keep the universe empty.
        if self.is_warming_up:
            return []
        # Select a subset of the stocks based on the indicator.
        # Example: 10 stocks furthest above their SMA.
        factor_by_symbol = {
            f.symbol: f.price / self._selection_data_by_symbol[f.symbol].indicator.current.value 
            for f in ready_stocks
        }
        return sorted(
            {k: v for k, v in factor_by_symbol.items() if v > 0}, 
            key=lambda symbol: factor_by_symbol[symbol]
        )[-100:]


class SelectionData:

    def __init__(self, algorithm, f):
        self._algorithm = algorithm
        self._price_scale_factor = f.price_scale_factor
        self.indicator = SimpleMovingAverage(21)

    def update(self, f):
        # If there hasn't been a split or dividend since the last trading
        # day, just update the indicator like normal.
        if f.price_scale_factor == self._price_scale_factor:
            return self.indicator.update(f.end_time, f.price)
        # Otherwise, reset the indicator and warm it up with the new 
        # adjusted history.
        self._price_scale_factor = f.price_scale_factor
        self.indicator.reset()
        history = self._algorithm.history[TradeBar](
            f.symbol, 
            self.indicator.warm_up_period, 
            Resolution.DAILY, 
            data_normalization_mode=DataNormalizationMode.SCALED_RAW
        )
        for bar in history:
            self.indicator.update(bar)
        return self.indicator.is_ready</pre>
	<pre class="csharp">public class EquityIndicatorUniverseSelectionAlgorithm : QCAlgorithm
{
    private Dictionary&lt;Symbol, SelectionData&gt; _selectionDataBySymbol = new();
    private Universe _universe;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        Settings.SeedInitialPrices = true;
        // Add a universe of US Equities based on an indicator.
        _universe = AddUniverse(_SelectAssets);
        // Add a warm-up period to warm up the indicators in the universe selection.
        SetWarmUp(TimeSpan.FromDays(60));
    }

    private IEnumerable&lt;Symbol&gt; _SelectAssets(IEnumerable&lt;Fundamental&gt; fundamentals)
    {
        // Update the indicator of all stocks in the universe dataset and
        // get the subset of stocks that have their indicator ready.
        var readyStocks = new List&lt;Fundamental&gt;();
        foreach (var f in fundamentals)
        {
            if (!_selectionDataBySymbol.TryGetValue(f.Symbol, out var sd))
            {
                sd = new SelectionData(this, f);
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
        // Select a subset of the stocks based on the indicator.
        // Example: 10 stocks furthest above their SMA.
        return readyStocks
            .Select(f => (f.Symbol, Factor: f.Price / _selectionDataBySymbol[f.Symbol].Indicator.Current.Value))
            .Where(t => t.Factor > 0)
            .OrderBy(t => t.Factor)
            .TakeLast(100)
            .Select(t => t.Symbol);
    }
}

public class SelectionData
{
    private QCAlgorithm _algorithm;
    private decimal _priceScaleFactor;
    public SimpleMovingAverage Indicator { get; }

    public SelectionData(QCAlgorithm algorithm, Fundamental f)
    {
        _algorithm = algorithm;
        _priceScaleFactor = f.PriceScaleFactor;
        Indicator = new SimpleMovingAverage(21);
    }

    public bool Update(Fundamental f)
    {
        // If there hasn't been a split or dividend since the last trading
        // day, just update the indicator like normal.
        if (f.PriceScaleFactor == _priceScaleFactor)
        {
            return Indicator.Update(f.EndTime, f.Price);
        }
        // Otherwise, reset the indicator and warm it up with the new
        // adjusted history.
        _priceScaleFactor = f.PriceScaleFactor;
        Indicator.Reset();
        var history = _algorithm.History&lt;TradeBar&gt;(
            f.Symbol,
            Indicator.WarmUpPeriod,
            Resolution.Daily,
            dataNormalizationMode: DataNormalizationMode.ScaledRaw
        );
        foreach (var bar in history)
        {
            Indicator.Update(bar);
        }
        return Indicator.IsReady;
    }
}</pre>
</div>
