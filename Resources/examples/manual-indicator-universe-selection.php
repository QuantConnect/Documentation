<h4>Example <?=$number?>: Universe Selection</h4>
<p>The following algorithm selects the stocks within 5% of the 1-year maximum price among the top 100 liquid stocks. To do so, we make use of <a href="/docs/v2/writing-algorithms/indicators/supported-indicators/maximum">Maximum</a> indicator to do so. Then, we hold the stocks with price &gt; <a href="/docs/v2/writing-algorithms/indicators/supported-indicators/exponential-moving-average">EMA</a> &gt; <a href="/docs/v2/writing-algorithms/indicators/supported-indicators/simple-moving-average">SMA</a>, which indicates an upward accelerating trend.</p>
<div class="section-example-container">
    <pre class="csharp">public class ManualIndicatorAlgorithm : QCAlgorithm
{
    private Dictionary&lt;Symbol, Maximum&gt; _maximumBySymbol = new();
    private Dictionary&lt;Symbol, SymbolData&gt; _symbolData = new();

    public override void Initialize()
    {
        SetStartDate(2021, 1, 1);
        SetEndDate(2021, 2, 1);
        
        // Select a popularity-based universe with indicators in a Selection function.
        AddUniverse(Selection);

        // Set a scheduled event to rebalance daily on the daily indicator signals.
        Schedule.On(
            DateRules.EveryDay(),
            TimeRules.At(9, 31),
            Rebalance
        );
    }

    private IEnumerable&lt;Symbol&gt; Selection(IEnumerable&lt;Fundamental&gt; fundamentals)
    {
        var selected = new List&lt;Symbol&gt;();

        // Initially filtered for the top 100 liquid stocks first.
        var filtered = fundamentals.OrderByDescending(f =&gt; f.DollarVolume)
            .Take(100)
            .ToList();

        foreach (var f in filtered)
        {
            if (!_maximumBySymbol.TryGetValue(f.Symbol, out var maximum))
            {
                maximum = new(252);
                // Warm up the Maximum indicator for its readiness to use immediately.
                var history = History&lt;TradeBar&gt;(f.Symbol, 252, Resolution.Daily);
                foreach (var bar in history)
                {
                    maximum.Update(bar.EndTime, bar.Close);
                }
                _maximumBySymbol[f.Symbol] = maximum;
            }
            else
            {
                // Update the indicator with the last known adjusted price daily.
                maximum.Update(f.EndTime, f.AdjustedPrice);
            }

            // Select to trade if the current price is within 5% of the maximum price of the last year.
            // Close to the maximum price provides evidence of high popularity for the fund to support the trend.
            if (f.AdjustedPrice &gt;= maximum * 0.95m)
            {
                selected.Add(f.Symbol);
            }
        }

        return selected;
    }

    private void Rebalance()
    {
        var symbolsToBuy = new List&lt;Symbol&gt;();
        
        foreach (var (symbol, symbolData) in _symbolData)
        {
            // Buy the stocks whose prices are above the EMA and the EMA is above the SMA, meaning their trend is upward accelerating.
            if (symbolData.IsReady &amp;&amp; Securities[symbol].Price &gt; symbolData.Ema &amp;&amp; symbolData.Ema &gt; symbolData.Sma)
            {
                symbolsToBuy.Add(symbol);
            }
        }

        // Equally invest in selected stocks to dissipate capital risk evenly.
        var count = symbolsToBuy.Count;
        if (count &gt; 0)
        {
            var targets = symbolsToBuy.Select(symbol =&gt; new PortfolioTarget(symbol, 1m / count)).ToList();
            // Liquidate the positions that are not on an upward trend or are not popular anymore.
            SetHoldings(targets, liquidateExistingHoldings: true);
        }
    }

    public override void OnSecuritiesChanged(SecurityChanges changes)
    {
        foreach (var added in changes.AddedSecurities)
        {
            // Instantiate a new instance of SymbolData object to hold indicators to use.
            _symbolData[added.Symbol] = new SymbolData(this, added.Symbol);
        }

        foreach (var removed in changes.RemovedSecurities)
        {
            // Remove the data subscription of indicators to release computation resources when leaving the universe.
            if (_symbolData.Remove(removed.Symbol, out var symbolData))
            {
                symbolData.Dispose();
            }
        }
    }

    private class SymbolData
    {
        private QCAlgorithm _algorithm;
        public Symbol Symbol { get; set; }
        public ExponentialMovingAverage Ema { get; set; }
        public SimpleMovingAverage Sma { get; set; }

        public bool IsReady =&gt; Ema.IsReady &amp;&amp; Sma.IsReady;

        public SymbolData(QCAlgorithm algorithm, Symbol symbol)
        {
            _algorithm = algorithm;
            Symbol = symbol;

            // Create an EMA and an SMA manual indicator for trade signal generation.
            Ema = new ExponentialMovingAverage(20);
            Sma = new SimpleMovingAverage(20);

            // Warm up the indicators to ensure their readiness to use them immediately.
            algorithm.WarmUpIndicator(symbol, Ema, Resolution.Daily);
            algorithm.WarmUpIndicator(symbol, Sma, Resolution.Daily);

            // Subscribe to the indicators to update daily price data for updated trade signal generation.
            algorithm.RegisterIndicator(symbol, Ema, Resolution.Daily);
            algorithm.RegisterIndicator(symbol, Sma, Resolution.Daily);
        }

        public void Dispose()
        {
            Ema.Reset();
            Sma.Reset();
            // Cancel data subscription to release computation resources.
            _algorithm.DeregisterIndicator(Ema);
            _algorithm.DeregisterIndicator(Sma);
        }
    }
}</pre>
    <pre class="python">class ManualIndicatorAlgorithm(QCAlgorithm):
    maximum_by_symbol = {}

    def initialize(self) -&gt; None:
        self.set_start_date(2021, 1, 1)
        self.set_end_date(2021, 2, 1)

        # Select a popularity-based universe with indicators in a Selection function.
        self._universe = self.add_universe(self.selection)

        # Set a scheduled event to rebalance daily on the daily indicator signals.
        self.schedule.on(
            self.date_rules.every_day(),
            self.time_rules.at(9, 31),
            self.rebalance
        )

    def selection(self, fundamentals: List[Fundamental]) -&gt; List[Symbol]:
        selected = []

        # Initially filtered for the top 100 liquid stocks first.
        filtered = sorted(fundamentals, key=lambda f: f.dollar_volume, reverse=True)[:100]

        for f in filtered:
            if f.symbol not in self.maximum_by_symbol:
                self.maximum_by_symbol[f.symbol] = Maximum(252)
                # Warm up the Maximum indicator to its readiness to use immediately.
                history = self.history[TradeBar](f.symbol, 252, Resolution.DAILY)
                for bar in history:
                    self.maximum_by_symbol[f.symbol].update(bar.end_time, bar.close)
            else:
                # Update the indicator with the last known adjusted price daily.
                self.maximum_by_symbol[f.symbol].update(f.end_time, f.adjusted_price)

            # Select to trade if the current price is within 5% of the maximum price of the last year.
            # Close to the maximum price provides evidence of high popularity for the fund to support the trend.
            if f.adjusted_price &gt;= self.maximum_by_symbol[f.symbol].current.value * 0.95:
                selected.append(f.symbol)

        return selected
                
    def rebalance(self) -&gt; None:
        def to_buy(symbol):
            security = self.securities[symbol]
            # Buy the stocks whose prices are above the EMA and the EMA is above the SMA, meaning their trend is upward accelerating.
            return security.price &gt; security.ema.current.value &gt; security.sma.current.value

        symbols_to_buy = [symbol for symbol in self._universe.selected if to_buy(symbol)]

        # Equally invest in the selected stocks to dissipate the capital risk evenly.
        count = len(symbols_to_buy)
        if count &gt; 0:
            targets = [PortfolioTarget(symbol, 1 / count) for symbol in symbols_to_buy]
            # Liquidate the positions that are not on an upward trend or are not popular anymore.
            self.set_holdings(targets, liquidate_existing_holdings=True)

    def on_securities_changed(self, changes: SecurityChanges) -&gt; None:
        for added in changes.added_securities:
            symbol = added.symbol
            # Create an EMA and an SMA manual indicator for trade signal generation.
            added.ema = ExponentialMovingAverage(20)
            added.sma = SimpleMovingAverage(20)

            # Warm up the indicators to ensure their readiness to use them immediately.
            self.warm_up_indicator(symbol, added.ema, Resolution.DAILY)
            self.warm_up_indicator(symbol, added.sma, Resolution.DAILY)

            # Subscribe to the indicators to update daily price data for updated trade signal generation.
            self.register_indicator(symbol, added.ema, Resolution.DAILY)
            self.register_indicator(symbol, added.sma, Resolution.DAILY)

        for removed in changes.removed_securities:
            # Cancel data subscription to release computation resources when leaving the universe.
            self.deregister_indicator(removed.ema)
            self.deregister_indicator(removed.sma)</pre>
</div>
