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
 <script class="csharp-result" type="text">
  {
    "Total Orders": "1151",
    "Average Win": "0.20%",
    "Average Loss": "-0.04%",
    "Compounding Annual Return": "35.159%",
    "Drawdown": "3.000%",
    "Expectancy": "0.199",
    "Start Equity": "100000",
    "End Equity": "110287.03",
    "Net Profit": "10.287%",
    "Sharpe Ratio": "2.657",
    "Sortino Ratio": "3.334",
    "Probabilistic Sharpe Ratio": "83.188%",
    "Loss Rate": "78%",
    "Win Rate": "22%",
    "Profit-Loss Ratio": "4.44",
    "Alpha": "0.238",
    "Beta": "0.016",
    "Annual Standard Deviation": "0.088",
    "Annual Variance": "0.008",
    "Information Ratio": "2.363",
    "Tracking Error": "0.204",
    "Treynor Ratio": "14.937",
    "Total Fees": "$1316.07",
    "Estimated Strategy Capacity": "$19000000.00",
    "Lowest Capacity Asset": "SPY R735QTJ8XC9X",
    "Portfolio Turnover": "663.03%",
    "OrderListHash": "e69482c60db804dcf33813bd6968e2a3"
}
 </script>
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
 <script class="python-result" type="text">
  {
    "Total Orders": "1151",
    "Average Win": "0.20%",
    "Average Loss": "-0.04%",
    "Compounding Annual Return": "35.159%",
    "Drawdown": "3.000%",
    "Expectancy": "0.199",
    "Start Equity": "100000",
    "End Equity": "110287.03",
    "Net Profit": "10.287%",
    "Sharpe Ratio": "2.657",
    "Sortino Ratio": "3.334",
    "Probabilistic Sharpe Ratio": "83.188%",
    "Loss Rate": "78%",
    "Win Rate": "22%",
    "Profit-Loss Ratio": "4.44",
    "Alpha": "0.238",
    "Beta": "0.016",
    "Annual Standard Deviation": "0.088",
    "Annual Variance": "0.008",
    "Information Ratio": "2.363",
    "Tracking Error": "0.204",
    "Treynor Ratio": "14.937",
    "Total Fees": "$1316.07",
    "Estimated Strategy Capacity": "$19000000.00",
    "Lowest Capacity Asset": "SPY R735QTJ8XC9X",
    "Portfolio Turnover": "663.03%",
    "OrderListHash": "b5fc1f1df6afd1758d08f6dd89fdf279"
}
 </script>
</div>
<h4>
 Example 2: Get Tick Data
</h4>
<p>
 The following algorithm calculates the bid and ask sizes of the latest SPY quote ticks. Then, buy SPY if bid size is greater than ask size, indicating supply is greater than demand. Else, sell if bid size is smaller than ask size, indicating supply is smaller than supply.
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
 <script class="csharp-result" type="text">
  {
    "Total Orders": "15",
    "Average Win": "0.35%",
    "Average Loss": "-0.19%",
    "Compounding Annual Return": "4.026%",
    "Drawdown": "1.400%",
    "Expectancy": "-0.059",
    "Start Equity": "100000",
    "End Equity": "100039.66",
    "Net Profit": "0.040%",
    "Sharpe Ratio": "0.294",
    "Sortino Ratio": "0",
    "Probabilistic Sharpe Ratio": "0%",
    "Loss Rate": "67%",
    "Win Rate": "33%",
    "Profit-Loss Ratio": "1.82",
    "Alpha": "-0.003",
    "Beta": "0.005",
    "Annual Standard Deviation": "0.013",
    "Annual Variance": "0",
    "Information Ratio": "-3.109",
    "Tracking Error": "0.455",
    "Treynor Ratio": "0.821",
    "Total Fees": "$27.11",
    "Estimated Strategy Capacity": "$0",
    "Lowest Capacity Asset": "SPY R735QTJ8XC9X",
    "Portfolio Turnover": "261.86%",
    "OrderListHash": "4161f34e9bc822b91229b5f1db068e2c"
}
 </script>
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
 <script class="python-result" type="text">
  {
    "Total Orders": "15",
    "Average Win": "0.35%",
    "Average Loss": "-0.19%",
    "Compounding Annual Return": "4.026%",
    "Drawdown": "1.400%",
    "Expectancy": "-0.059",
    "Start Equity": "100000",
    "End Equity": "100039.66",
    "Net Profit": "0.040%",
    "Sharpe Ratio": "0.294",
    "Sortino Ratio": "0",
    "Probabilistic Sharpe Ratio": "0%",
    "Loss Rate": "67%",
    "Win Rate": "33%",
    "Profit-Loss Ratio": "1.82",
    "Alpha": "-0.003",
    "Beta": "0.005",
    "Annual Standard Deviation": "0.013",
    "Annual Variance": "0",
    "Information Ratio": "-3.109",
    "Tracking Error": "0.455",
    "Treynor Ratio": "0.821",
    "Total Fees": "$27.11",
    "Estimated Strategy Capacity": "$0",
    "Lowest Capacity Asset": "SPY R735QTJ8XC9X",
    "Portfolio Turnover": "261.86%",
    "OrderListHash": "6247c62b1361f4ae72fe3f24f68758bb"
}
 </script>
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
 <script class="csharp-result" type="text">
  {
    "Total Orders": "2828",
    "Average Win": "0.04%",
    "Average Loss": "-0.02%",
    "Compounding Annual Return": "29.809%",
    "Drawdown": "22.100%",
    "Expectancy": "0.738",
    "Start Equity": "100000",
    "End Equity": "129778.51",
    "Net Profit": "29.779%",
    "Sharpe Ratio": "1.012",
    "Sortino Ratio": "1.227",
    "Probabilistic Sharpe Ratio": "46.273%",
    "Loss Rate": "38%",
    "Win Rate": "62%",
    "Profit-Loss Ratio": "1.78",
    "Alpha": "-0.044",
    "Beta": "1.374",
    "Annual Standard Deviation": "0.222",
    "Annual Variance": "0.049",
    "Information Ratio": "0.174",
    "Tracking Error": "0.17",
    "Treynor Ratio": "0.164",
    "Total Fees": "$2829.55",
    "Estimated Strategy Capacity": "$18000000.00",
    "Lowest Capacity Asset": "MSFT R735QTJ8XC9X",
    "Portfolio Turnover": "2.61%",
    "OrderListHash": "c1d8afe58a5596b44c02bf98c2519772"
}
 </script>
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
 <script class="python-result" type="text">
  {
    "Total Orders": "2828",
    "Average Win": "0.04%",
    "Average Loss": "-0.02%",
    "Compounding Annual Return": "29.809%",
    "Drawdown": "22.100%",
    "Expectancy": "0.738",
    "Start Equity": "100000",
    "End Equity": "129778.51",
    "Net Profit": "29.779%",
    "Sharpe Ratio": "1.012",
    "Sortino Ratio": "1.227",
    "Probabilistic Sharpe Ratio": "46.273%",
    "Loss Rate": "38%",
    "Win Rate": "62%",
    "Profit-Loss Ratio": "1.78",
    "Alpha": "-0.044",
    "Beta": "1.374",
    "Annual Standard Deviation": "0.222",
    "Annual Variance": "0.049",
    "Information Ratio": "0.174",
    "Tracking Error": "0.17",
    "Treynor Ratio": "0.164",
    "Total Fees": "$2829.55",
    "Estimated Strategy Capacity": "$18000000.00",
    "Lowest Capacity Asset": "MSFT R735QTJ8XC9X",
    "Portfolio Turnover": "2.61%",
    "OrderListHash": "4cd25da864d29870b6e233489443f87a"
}
 </script>
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
 <script class="csharp-result" type="text">
  {
    "Total Orders": "4",
    "Average Win": "0.19%",
    "Average Loss": "0%",
    "Compounding Annual Return": "35.826%",
    "Drawdown": "4.000%",
    "Expectancy": "0",
    "Start Equity": "100000",
    "End Equity": "108020",
    "Net Profit": "8.020%",
    "Sharpe Ratio": "1.961",
    "Sortino Ratio": "2.46",
    "Probabilistic Sharpe Ratio": "70.998%",
    "Loss Rate": "0%",
    "Win Rate": "100%",
    "Profit-Loss Ratio": "0",
    "Alpha": "0.108",
    "Beta": "-0.252",
    "Annual Standard Deviation": "0.117",
    "Annual Variance": "0.014",
    "Information Ratio": "1.237",
    "Tracking Error": "0.574",
    "Treynor Ratio": "-0.907",
    "Total Fees": "$2.00",
    "Estimated Strategy Capacity": "$5000.00",
    "Lowest Capacity Asset": "SPY R735QTJ8XC9X",
    "Portfolio Turnover": "0.36%",
    "OrderListHash": "7ad1b9e17e003e11ed9a9a514b22cc02"
}
 </script>
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
 <script class="python-result" type="text">
  {
    "Total Orders": "4",
    "Average Win": "0.08%",
    "Average Loss": "0%",
    "Compounding Annual Return": "-26.545%",
    "Drawdown": "11.700%",
    "Expectancy": "-0.5",
    "Start Equity": "100000",
    "End Equity": "92522",
    "Net Profit": "-7.478%",
    "Sharpe Ratio": "-1.581",
    "Sortino Ratio": "-1.596",
    "Probabilistic Sharpe Ratio": "5.540%",
    "Loss Rate": "50%",
    "Win Rate": "50%",
    "Profit-Loss Ratio": "0",
    "Alpha": "-0.069",
    "Beta": "0.282",
    "Annual Standard Deviation": "0.129",
    "Annual Variance": "0.017",
    "Information Ratio": "0.838",
    "Tracking Error": "0.329",
    "Treynor Ratio": "-0.726",
    "Total Fees": "$2.00",
    "Estimated Strategy Capacity": "$0",
    "Lowest Capacity Asset": "SPY R735QTJ8XC9X",
    "Portfolio Turnover": "0.35%",
    "OrderListHash": "cc516445c39dd38e80800109f549a451"
}
 </script>
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
 <script class="csharp-result" type="text">
  {
    "Total Orders": "0",
    "Average Win": "0%",
    "Average Loss": "0%",
    "Compounding Annual Return": "0%",
    "Drawdown": "0%",
    "Expectancy": "0",
    "Start Equity": "100000",
    "End Equity": "100000",
    "Net Profit": "0%",
    "Sharpe Ratio": "0",
    "Sortino Ratio": "0",
    "Probabilistic Sharpe Ratio": "0%",
    "Loss Rate": "0%",
    "Win Rate": "0%",
    "Profit-Loss Ratio": "0",
    "Alpha": "0",
    "Beta": "0",
    "Annual Standard Deviation": "0",
    "Annual Variance": "0",
    "Information Ratio": "0",
    "Tracking Error": "0",
    "Treynor Ratio": "0",
    "Total Fees": "$0.00",
    "Estimated Strategy Capacity": "$0",
    "Lowest Capacity Asset": "",
    "Portfolio Turnover": "0%",
    "OrderListHash": ""
}
 </script>
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
 <script class="python-result" type="text">
  {
    "Total Orders": "0",
    "Average Win": "0%",
    "Average Loss": "0%",
    "Compounding Annual Return": "0%",
    "Drawdown": "0%",
    "Expectancy": "0",
    "Start Equity": "100000",
    "End Equity": "100000",
    "Net Profit": "0%",
    "Sharpe Ratio": "0",
    "Sortino Ratio": "0",
    "Probabilistic Sharpe Ratio": "0%",
    "Loss Rate": "0%",
    "Win Rate": "0%",
    "Profit-Loss Ratio": "0",
    "Alpha": "0",
    "Beta": "0",
    "Annual Standard Deviation": "0",
    "Annual Variance": "0",
    "Information Ratio": "0",
    "Tracking Error": "0",
    "Treynor Ratio": "0",
    "Total Fees": "$0.00",
    "Estimated Strategy Capacity": "$0",
    "Lowest Capacity Asset": "",
    "Portfolio Turnover": "0%",
    "OrderListHash": ""
}
 </script>
</div>

<?
$number = 6;
include(DOCS_RESOURCES."/examples/market-session-as-history.php");
?>
