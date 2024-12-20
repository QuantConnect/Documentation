<p>The following examples demonstrate some common practices for implementing rolling windows.</p>

<h4>Example 1: Price Actions</h4>
<? include(DOCS_RESOURCES."/examples/take-profit-stop-loss-example.html"); ?>

<h4>Example 2: Bid-Ask Spread</h4>
<p>The following algorithm trades the microeconomy of SPY's supply-demand relationship. We buy SPY if the current bid-ask spread is less than the average of the last 20 quote bars, indicating demand is approaching supply during short SPY and vice versa. We use a rolling window to save the last 20 quote data and save the calculated spread.</p>
<div class="section-example-container">
    <pre class="csharp">public class RollingWindowAlgorithm : QCAlgorithm
{
    private Symbol _spy;
    // Set up a rolling window to hold the last 20 bar's bid-ask spread for trade signal generation.
    private RollingWindow&lt;decimal&gt; _windows = new(20);

    public override void Initialize()
    {
        SetStartDate(2020, 2, 20);
        SetEndDate(2020, 2, 27);

        // Request SPY data for signal generation and trading.
        _spy = AddEquity("SPY", Resolution.Minute).Symbol;

        // Warm up for the rolling window with quote data.
        var history = History&lt;QuoteBar&gt;(_spy, 20, Resolution.Minute);
        foreach (var bar in history)
        {
            _windows.Add(bar.Ask.Close - bar.Bid.Close);
        }
    }
    
    public override void OnData(Slice slice)
    {
        if (slice.QuoteBars.TryGetValue(_spy, out var bar))
        {
            // Update the window with the current bid-ask spread.
            var spread = bar.Ask.Close - bar.Bid.Close;
            _windows.Add(spread);

            // Buy if the current spread is smaller than average, indicating demand is approaching supply. Buy force will drive up prices.
            if (spread &lt; _windows.Average())
            {
                SetHoldings(_spy, -0.5m);
            }
            // Short if the current spread is larger than average, indicating supply is gradually overwhelming demand.
            else if (spread &gt; _windows.Average())
            {
                SetHoldings(_spy, 0.5m);
            }
        }
    }
}</pre>
    <pre class="python">class RollingWindowAlgorithm(QCAlgorithm):
    def initialize(self) -&gt; None:
        self.set_start_date(2020, 2, 20)
        self.set_end_date(2020, 2, 27)
        
        # Request SPY data for signal generation and trading.
        self.spy = self.add_equity("SPY", Resolution.MINUTE).symbol

        # Set up a rolling window to hold the last 20 bar's bid-ask spread for trade signal generation.
        self.windows = RollingWindow[float](20)
        # Warm up for the rolling window with quote data.
        history = self.history[QuoteBar](self.spy, 20, Resolution.MINUTE)
        for bar in history:
            self.windows.add(bar.ask.close - bar.bid.close)

    def on_data(self, slice: Slice) -&gt; None:
        bar = slice.quote_bars.get(self.spy)
        if bar:
            # Update the window with the current bid-ask spread.
            spread = bar.ask.close - bar.bid.close
            self.windows.add(spread)

            # Buy if the current spread is smaller than average, indicating demand is approaching supply. Buy force will drive up prices.
            if spread &lt; np.mean(list(self.windows)):
                self.set_holdings(self.spy, -0.5)
            # Short if the current spread is larger than average, indicating supply is gradually overwhelming demand.
            elif spread &gt; np.mean(list(self.windows)):
                self.set_holdings(self.spy, 0.5)</pre>
</div>

<h4>Example 3: Trend Acceleration</h4>
<p>The following algorithm trades the second derivative of EMA indicated trend, especially the difference between lagged EMA values. If the daily EMA difference increases while being lower than the current price, we estimate the uptrend is accelerating, hence buying SPY. Otherwise, if the price is below the EMA while the daily EMA difference is decelerating, we short SPY.</p>
<div class="section-example-container">
    <pre class="csharp">public class RollingWindowAlgorithm : QCAlgorithm
{
    private Symbol _spy;
    private ExponentialMovingAverage _ema;

    public override void Initialize()
    {
        SetStartDate(2021, 1, 1);
        SetEndDate(2022, 1, 1);

        // Request SPY data for trading.
        _spy = AddEquity("SPY", Resolution.Minute).Symbol;
        
        // Set up an automatic EMA indicator for trade signal generation.
        _ema = EMA(_spy, 20, Resolution.Daily);
        //Using the rolling window in the EMA indicator, we adjust its size so it can compare with previous data points.
        _ema.Window.Size = 3;

        // Schedule an event to rebalance SPY position at the daily market open.
        Schedule.On(
            DateRules.EveryDay(_spy),
            TimeRules.AfterMarketOpen(_spy, 0),
            Rebalance
        );

        SetWarmUp(23, Resolution.Daily);
    }
    
    private void Rebalance()
    {
        if (_ema.Window.IsReady)
        {
            // Buy if the current EMA increases with acceleration, indicating a strong up trend.
            if (_ema.Window[1] &lt; _ema &amp;&amp; _ema.Window[0] - _ema.Window[1] &gt; _ema.Window[1] - _ema.Window[2])
            {
                SetHoldings(_spy, 0.5m);
            }
            // Short if the current EMA decreases with acceleration, indicating a strong downtrend.
            else if (_ema.Window[1] &gt; _ema &amp;&amp; _ema.Window[0] - _ema.Window[1] &lt; _ema.Window[1] - _ema.Window[2])
            {
                SetHoldings(_spy, 0.5m);
            }
            // Liquidate if no strong trend is indicated.
            else if (Portfolio.Invested)
            {
                Liquidate(_spy);
            }
        }
    }
}</pre>
    <pre class="python">class RollingWindowAlgorithm(QCAlgorithm):
    def initialize(self) -&gt; None:
        self.set_start_date(2021, 8, 1)
        self.set_end_date(2022, 11, 1)
        
        # Request SPY data for trading.
        self.spy = self.add_equity("SPY", Resolution.MINUTE).symbol

        # Set up an automatic EMA indicator for trade signal generation.
        self._ema = self.ema(self.spy, 20, Resolution.DAILY)
        # Using the rolling window in the EMA indicator, we adjust its size so it can compare with previous data points.
        self._ema.window.size = 3

        # Schedule an event to rebalance SPY position at the daily market open.
        self.schedule.on(
            self.date_rules.every_day(self.spy),
            self.time_rules.after_market_open(self.spy, 0),
            self.rebalance
        )

        self.set_warm_up(23, Resolution.DAILY)

    def rebalance(self) -&gt; None:
        if self._ema.window.is_ready:
            # Buy if the current EMA increases with acceleration, indicating a strong up trend.
            if self._ema.window[1].value &lt; self._ema.current.value and \
            self._ema.window[0].value - self._ema.window[1].value &gt; self._ema.window[1].value - self._ema.window[2].value:
                self.set_holdings(self.spy, 0.5)
            # Short if the current EMA decreases with acceleration, indicating a strong downtrend.
            elif self._ema.window[1].value &gt; self._ema.current.value and \
            self._ema.window[0].value - self._ema.window[1].value &lt; self._ema.window[1].value - self._ema.window[2].value:
                self.set_holdings(self.spy, -0.5)
            # Liquidate if no strong trend is indicated.
            elif self.portfolio.invested:
                self.liquidate(self.spy)</pre>
</div>

<h4>Other Examples</h4>
<p>For more examples, see the following algorithms:</p>
<? include(DOCS_RESOURCES."/rolling-window/examples.html"); ?>
