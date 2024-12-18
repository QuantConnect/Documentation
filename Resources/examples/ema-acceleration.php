<h4>Example <?=$number?>: EMA Acceleration</h4>
<p>The following algorithm long SPY by uptrend indicated with EMA indicator, with EMA above previous level while accelerating. Inversely, we short SPY by downtrend, indicated with EMA below previous level while decelerating. To compare the previous levels, we make use of the in0built rolling window in the EMA indicator.</p>
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
        
        // Set up an automatical EMA indicator for trade signal generation.
        _ema = EMA(_spy, 20, Resolution.Daily);
        // Making use of the rolling window in the EMA indicator, we adjust it size so it can compare with previous data points.
        _ema.Window.Size = 3;

        // Schedule an event to rebalance SPY position at daily market open.
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
            // Buy if the current EMA is increasing with acceleration, indicating strong up trend.
            if (_ema.Window[1] &lt; _ema &amp;&amp; _ema.Window[0] - _ema.Window[1] &gt; _ema.Window[1] - _ema.Window[2])
            {
                SetHoldings(_spy, 0.5m);
            }
            // Short if the current EMA is decreasing with acceleration, indicating strong down trend.
            else if (_ema.Window[1] &gt; _ema &amp;&amp; _ema.Window[0] - _ema.Window[1] &lt; _ema.Window[1] - _ema.Window[2])
            {
                SetHoldings(_spy, 0.5m);
            }
            // Liquidate if no strong trend indicated.
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

        # Set up an automatical EMA indicator for trade signal generation.
        self._ema = self.ema(self.spy, 20, Resolution.DAILY)
        # Making use of the rolling window in the EMA indicator, we adjust it size so it can compare with previous data points.
        self._ema.window.size = 3

        # Schedule an event to rebalance SPY position at daily market open.
        self.schedule.on(
            self.date_rules.every_day(self.spy),
            self.time_rules.after_market_open(self.spy, 0),
            self.rebalance
        )

        self.set_warm_up(23, Resolution.DAILY)

    def rebalance(self) -&gt; None:
        if self._ema.window.is_ready:
            # Buy if the current EMA is increasing with acceleration, indicating strong up trend.
            if self._ema.window[1].value &lt; self._ema.current.value and \
            self._ema.window[0].value - self._ema.window[1].value &gt; self._ema.window[1].value - self._ema.window[2].value:
                self.set_holdings(self.spy, 0.5)
            # Short if the current EMA is decreasing with acceleration, indicating strong down trend.
            elif self._ema.window[1].value &gt; self._ema.current.value and \
            self._ema.window[0].value - self._ema.window[1].value &lt; self._ema.window[1].value - self._ema.window[2].value:
                self.set_holdings(self.spy, -0.5)
            # Liquidate if no strong trend indicated.
            elif self.portfolio.invested:
                self.liquidate(self.spy)</pre>
</div>