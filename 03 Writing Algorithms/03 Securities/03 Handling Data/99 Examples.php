<p>
 The following examples demonstrate some common practices for handling data.
</p>
<h4>
 Example 1: Get QuoteBar Data
</h4>
<p>
 The following algorithm updates an
 <a href="/docs/v2/writing-algorithms/indicators/key-concepts">
  indicator
 </a>
 with the mid-price of a quote bar to increase the accuracy of the indicator values for illiquid assets. Then, make use of the SMA indicator to trade the SMA cross strategy.
</p>
<div class="section-example-container testable">
 <pre class="csharp">public class HandlingSecuritiesDataAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    // Initialize a new instance of a SimpleMovingAverage object.
    private SimpleMovingAverage _indicator = new(250);

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);

        _symbol = AddEquity("SPY").Symbol;
        
        SetWarmUp(TimeSpan.FromDays(350));
    }

    public override void OnData(Slice slice)
    {
        // Check if the QuoteBars contain SPY quote data.
        if (!slice.QuoteBars.ContainsKey(_symbol))
        {
            return;
        }
        var quoteBar = slice.QuoteBars[_symbol];
        // Calculate the mid price by averaging the bid and ask price.
        var midPrice = (quoteBar.Bid.Close + quoteBar.Ask.Close) * 0.5m;
        // Update the SMA indicator with the mid price.
        _indicator.Update(quoteBar.EndTime, midPrice);

        // Wait for the algorithm and indicator to be warmed up.
        if (IsWarmingUp || !_indicator.IsReady)
        {
            return;
        }
        // Trade SMA cross strategy.
        SetHoldings(_symbol, _indicator > midPrice ? -0.5m : 0.5m);
    }
}</pre>
<pre class="python">class HandlingSecuritiesDataAlgorithm(QCAlgorithm):
  
    def initialize(self) -&gt; None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)

        self._symbol = self.add_equity("SPY").symbol
        self._indicator = SimpleMovingAverage(250)
        
        self.set_warm_up(timedelta(350))

    def on_data(self, slice: Slice) -&gt; None:
        # Check if the QuoteBars contain SPY quote data.
        if self._symbol not in slice.quote_bars:
            return
        quote_bar = slice.quote_bars[self._symbol]
        # Calculate the mid price by averaging the bid and ask price.
        mid_price = (quote_bar.bid.close + quote_bar.ask.close) * 0.5
        # Update the SMA indicator with the mid price.
        self._indicator.update(quote_bar.end_time, mid_price)
    
        # Wait for the algorithm and indicator to be warmed up.
        if self.is_warming_up or not self._indicator.is_ready:
            return
        # Trade SMA cross strategy.
        weight = -0.5 if self._indicator.current.value > mid_price else 0.5
        self.set_holdings(self._symbol, weight)</pre>
</div>
<h4>
 Example 2: Get Tick Data
</h4>
<p>
 The following algorithm calculates the bid and ask sizes of the latest SPY quote ticks and then trades at the top of each hour. 
 During each rebalance, it buys SPY if bid size is greater than ask size since supply is greater than demand in this case. 
 If bid size is smaller than ask size, it sells SPY since demand is greater than supply in this case.
</p>
<div class="section-example-container testable">
 <pre class="csharp">public class BidAskSizeTickAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    // Set up variables to save the bid and ask sizes.
    private decimal _bidSize = 0m, _askSize = 0m;
    private int _hour = -1;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 9, 10);
        // Seed the price of each asset with its last known price to 
        // avoid trading errors.
        SetSecurityInitializer(
            new BrokerageModelSecurityInitializer(
                BrokerageModel, new FuncSecuritySeeder(GetLastKnownPrices)
            )
        );
        _symbol = AddEquity("SPY", Resolution.Tick).Symbol;
    }

    public override void OnData(Slice slice)
    {
        // Check if the Ticks object contain SPY tick data.
        if (slice.Ticks.TryGetValue(_symbol, out var ticks))
        {            
            // Iterate all the ticks to calculate the bid and ask sizes.
            foreach (var tick in ticks.Where(tick => tick.TickType == TickType.Quote))
            {
                // Update the bid or ask size.
                _bidSize += tick.BidSize;
                _askSize += tick.AskSize;
            }
        }
        // Trade at the top of each hour.
        if (slice.Time.Hour != _hour)
        {
            // Invest based on supply-demand relationship from all ticks.
            // If bid size is above ask size, supply is greater than 
            // demand, which drives the price up. Otherwise, supply is 
            // lower than demand, which drives the price down.
            SetHoldings(_symbol, _bidSize > _askSize ? 0.5m : -0.5m);
            // Reset the bid and ask size variables.
            _bidSize = 0;
            _askSize = 0;
            _hour = slice.Time.Hour;
        }
    }
}</pre>
<pre class="python">class BidAskSizeTickAlgorithm(QCAlgorithm):
    # Set up variables to save the bid and ask sizes.
    _bid_size = 0
    _ask_size = 0
    _hour = -1

    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 9, 10)
        # Seed the price of each asset with its last known price to 
        # avoid trading errors.
        self.set_security_initializer(
            BrokerageModelSecurityInitializer(
                self.brokerage_model, 
                FuncSecuritySeeder(self.get_last_known_prices)
            )
        )
        self._symbol = self.add_equity('SPY', Resolution.TICK).symbol

    def on_data(self, slice: Slice) -> None:
        # Check if the Slice contain SPY tick data.
        ticks = slice.ticks.get(self._symbol)
        if ticks:
            # Iterate all the ticks to calculate the bid and ask sizes.
            for tick in ticks:
                # Make sure the tick data is a quote instead of a trade.
                if tick.tick_type == TickType.QUOTE:
                    # Update the bid and ask sizes.
                    self._bid_size += tick.bid_size
                    self._ask_size += tick.ask_size
        # Trade at the top of each hour.
        if slice.time.hour != self._hour:
            # Invest based on supply-demand relationship from all ticks.
            # If bid size is above ask size, supply is greater than 
            # demand, which drives the price up. Otherwise, supply is 
            # lower than demand, which drives the price down.
            self.set_holdings(
                self._symbol, 0.5 if self._bid_size > self._ask_size else -0.5
            )
            # Reset the bid and ask size variables.
            self._bid_size = 0
            self._ask_size = 0
            self._hour = slice.time.hour</pre>
</div>
<h4>
 Example 3: Get US Equity Fundamental Data
</h4>
<p>
 The following algorithm gets the latest
 <a href="/docs/v2/writing-algorithms/securities/asset-classes/us-equity/corporate-fundamentals">
  fundamental data
 </a>
 of all the US Equities in the algorithm. We invest in the stocks with the highest PE Ratio equally to ride on the popularity.
</p>
<div class="section-example-container testable">
 <pre class="csharp">public class HandlingSecuritiesDataAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);

        // Request data for a selected list of equities for trading.
        foreach (var ticker in new[] { "AAPL", "MSFT", "TSLA", "GOOG", "AMZN" })
        {
            AddEquity(ticker);
        }
    }

    public override void OnData(Slice slice)
    {
        var peRatios = new Dictionary&lt;Symbol, double&gt;();

        // Iterate active security objects.
        foreach (var security in ActiveSecurities.Values)
        {
            // Get the Fundamental cache.
            var fundamental = security.Fundamentals;
            // Get the sector code.
            var sectorCode = fundamental.AssetClassification.MorningstarSectorCode;

            peRatios[security.Symbol] = fundamental.ValuationRatios.PERatio;
        }
        
        // Sort by PE ratio to get the most popular stocks.
        var sortedByPeRatio = peRatios.OrderByDescending(x =&gt; x.Value)
            .ToDictionary(x =&gt; x.Key, x =&gt; x.Value);
        var targets = sortedByPeRatio
            .Take(3)
            .Select(x =&gt; new PortfolioTarget(x.Key, 1m / 3m))
            .ToList();
        targets.AddRange(sortedByPeRatio
            .TakeLast(2)
            .Select(x =&gt; new PortfolioTarget(x.Key, 0m))
            .ToList());
        // Invest equally in the highest PE Ratio stocks for evenly dissipate capital risk.
        SetHoldings(targets);
    }
}</pre>
<pre class="python">class HandlingSecuritiesDataAlgorithm(QCAlgorithm):
    def initialize(self) -&gt; None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)

        # Request data for a selected list of equities for trading.
        for ticker in ["AAPL", "MSFT", "TSLA", "GOOG", "AMZN"]:
            self.add_equity(ticker)

    def on_data(self, slice: Slice) -&gt; None:
        pe_ratios = {}

        # Iterate active security objects.
        for kvp in self.active_securities:
            symbol = kvp.key
            security = kvp.value
            
            # Get the Fundamental cache.
            fundamental = security.fundamentals
            # Get the sector code.
            sector_code = fundamental.asset_classification.morningstar_sector_code
        
            pe_ratios[symbol] = fundamental.valuation_ratios.pe_ratio
        
        # Sort by PE ratio to get the most popular stocks.
        sorted_by_pe_ratio = sorted(pe_ratios.items(), key=lambda x: x[1])
        targets = [PortfolioTarget(x[0], 1/3) for x in sorted_by_pe_ratio[-3:]]
        targets.extend([PortfolioTarget(x[0], 0) for x in sorted_by_pe_ratio[:2]])
        # Invest equally in the highest PE Ratio stocks for evenly dissipate capital risk.
        self.set_holdings(targets)</pre>
</div>
<h4>
 Example 4: Get Option Greeks
</h4>
<p>
 The following algorithm gets SPY
 <a href="/docs/v2/writing-algorithms/securities/asset-classes/equity-options/greeks-and-implied-volatility/key-concepts">
  Option Greek
 </a>
 values. Sell a straddle with the ATM options, selected by Option Delta (closest to 0.5).
</p>
<div class="section-example-container testable">
 <pre class="csharp">public class HandlingSecuritiesDataAlgorithm : QCAlgorithm
{
    private Symbol _symbol;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);

        // Add an SPY Option universe.
        var option = AddOption("SPY");
        // Set the Option universe filter.
        option.SetFilter(x =&gt; x.IncludeWeeklys().Strikes(-5, 5).Expiration(7, 60));
        // Save a reference of the canonical symbol.
        _symbol = option.Symbol;
    }

    public override void OnData(Slice slice)
    {
        // Try to get the Option contracts within the Option chain.
        if (!Portfolio.Invested &amp;&amp; slice.OptionChains.TryGetValue(_symbol, out var chain))
        {
            foreach (var contract in chain)
            {
                // Get the implied volatility and greeks of each Option contract.
                var iv = contract.ImpliedVolatility;
                var greeks = contract.Greeks;
                var delta = greeks.Delta;
                var gamma = greeks.Gamma;
                var vega = greeks.Vega;
                var theta = greeks.Theta;
                var rho = greeks.Rho;
            }

            // Invest in a straddle strategy to capitalize the volatility
            // Trade the ATM options with the closest expiry after 7 days.
            var expiry = chain.Min(x =&gt; x.Expiry);
            var selected = chain.Where(x =&gt; x.Expiry == expiry)
                .MinBy(x =&gt; Math.Abs(x.Greeks.Delta - 0.5m));
            var optionStrategy = OptionStrategies.Straddle(selected.Symbol.Canonical, selected.Strike, selected.Expiry);
            Sell(optionStrategy, 1);
        }
    }
}</pre>
<pre class="python">class HandlingSecuritiesDataAlgorithm(QCAlgorithm):
    def initialize(self) -&gt; None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)

        # Add an SPY Option universe.
        option = self.add_option("SPY")
        # Set the Option universe filter.
        option.set_filter(lambda x: x.include_weeklys().strikes(-5, 5).expiration(0, 60))
        # Save a reference of the canonical symbol.
        self._symbol = option.symbol

    def on_data(self, slice: Slice) -&gt; None:
        # Try to get the Option contracts within the Option chain.
        chain = slice.option_chains.get(self._symbol)
        if not self.portfolio.invested and chain:
            for contract in chain:
                # Get the implied volatility and greeks of each Option contract.
                iv = contract.implied_volatility
                greeks = contract.greeks
                delta = greeks.delta
                gamma = greeks.gamma
                vega = greeks.vega
                theta = greeks.theta
                rho = greeks.rho

            # Invest in a straddle strategy to capitalize the volatility
            # Trade the ATM options with the closest expiry after 7 days.
            expiry = min(x.expiry for x in chain)
            selected = sorted([x for x in chain if x.expiry == expiry],
                key=lambda x: abs(x.greeks.delta - 0.5))[0]
            option_strategy = OptionStrategies.straddle(selected.symbol.canonical, selected.strike, selected.expiry)
            self.sell(option_strategy, 1)</pre>
</div>
<h4>
 Example 5: Get Asset Sentiment Values
</h4>
<p>
 The following example gets sentiment values from the
 <a href="/datasets/brain-sentiment-indicator">
  Brain Sentiment Indicator
 </a>
 dataset. It simply buys AAPL when the sentiment is positive and liquidates the position when the sentiment is negative.
</p>
<div class="section-example-container testable">
 <pre class="csharp">public class HandlingSecuritiesDataAlgorithm : QCAlgorithm
{
    private Symbol _symbol, _dataset7DaySymbol;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        // Seed the price of AAPL with its last known price to avoid trading errors.
        SetSecurityInitializer(
            new BrokerageModelSecurityInitializer(BrokerageModel, new FuncSecuritySeeder(GetLastKnownPrices))
        );
        // Add the 7-day sentiment data for AAPL.
        _symbol = AddEquity("AAPL", Resolution.Daily).Symbol;
        _dataset7DaySymbol = AddData&lt;BrainSentimentIndicator7Day&gt;(_symbol).Symbol;
    }

    public override void OnData(Slice slice)
    {
        // Check if the current slice contains the 7-day sentiment data.
        if (slice.ContainsKey(_dataset7DaySymbol))
        {
            var dataPoint = slice[_dataset7DaySymbol];
            // Log the sentiment value.
            Log($"{_dataset7DaySymbol} sentiment at {slice.Time}: {dataPoint.Sentiment}");

            // Invest if the sentiment score is above 0, which indicates positive sentiment.
            if (dataPoint.Sentiment &gt; 0m)
            {
                MarketOrder(_symbol, 1);
            }
            // Liquidate otherwise.
            else if (dataPoint.Sentiment &lt; 0m &amp;&amp; Portfolio[_symbol].Invested)
            {
                Liquidate(_symbol);
            }
        }
    }
}</pre>
<pre class="python">class HandlingSecuritiesDataAlgorithm(QCAlgorithm):
    def initialize(self) -&gt; None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        # Seed the price of AAPL with its last known price to avoid trading errors.
        self.set_security_initializer(
            BrokerageModelSecurityInitializer(
                self.brokerage_model, 
                FuncSecuritySeeder(self.get_last_known_prices)
            )
        )
        # Add the 7-day sentiment data for AAPL.
        self._symbol = self.add_equity("AAPL", Resolution.DAILY).symbol
        self._dataset_7day_symbol = self.add_data(BrainSentimentIndicator7Day, self._symbol).symbol

    def on_data(self, slice: Slice) -&gt; None:
        # Check if the current slice contains the 7-day sentiment data.
        if slice.contains_key(self._dataset_7day_symbol):
            data_point = slice[self._dataset_7day_symbol]
            # Log the sentiment value.
            self.log(f"{self._dataset_7day_symbol} sentiment at {slice.time}: {data_point.sentiment}")
        
            # Invest if the sentiment score is above 0, which indicates positive sentiment.
            if data_point.sentiment &gt; 0:
                self.market_order(self._symbol, 1)
            # Liquidate otherwise.
            elif data_point.sentiment &lt; 0 and self.portfolio[self._symbol].invested:
                self.liquidate(self._symbol)</pre>
</div>

<?
$number = 6;
include(DOCS_RESOURCES."/examples/market-session-as-history.php");
?>
