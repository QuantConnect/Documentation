<p>The following examples demonstrate some common practices for implementing rolling windows.</p>

<h4>Example 1: Price Actions</h4>
<p>Using <code>RollingWindow</code> to cache the last 3 trade bars, the following algorithm could identify volume contraction breakout price action pattern and buy SPY accordingly to ride on the capital inflow. We take 2% profit and stop loss at 1%.</p>
<div class="section-example-container">
    <pre class="csharp">public class RollingWindowAlgorithm : QCAlgorithm
{
    private Symbol _spy;
    // Set up a rolling window to hold the last 3 trade bars for price action detection as trade signal.
    private RollingWindow&lt;TradeBar&gt; _windows = new(3);

    public override void Initialize()
    {
        SetStartDate(2021, 1, 1);
        SetEndDate(2022, 1, 1);

        // Request SPY data for signal generation &amp;&amp; trading.
        _spy = AddEquity("SPY", Resolution.Minute).Symbol;

        // Warm up for the rolling window.
        var history = History&lt;TradeBar&gt;(_spy, 3, Resolution.Minute);
        foreach (var bar in history)
        {
            _windows.Add(bar);
        }
    }
    
    public override void OnData(Slice slice)
    {
        if (slice.Bars.TryGetValue(_spy, out var bar))
        {
            // Trade the price action if the previous bars fulfill a contraction breakout.
            if (ContractionAction() &amp;&amp; BreakoutAction(bar.Close))
            {
                SetHoldings(_spy, 0.5m);
            }

            // Update the window with the current bar.
            _windows.Add(bar);
        }
    }

    private bool ContractionAction()
    {
        // We trade contraction type price action, where the buying preesure is increasing.
        // 1. The last 3 bars are green.
        // 2. The price is increasing in trend.
        // 3. The trading Volume is increasing as well.
        // 4. The range of the bars are decreasing.
        return _windows[2].Close &gt; _windows[2].Open &amp;&amp;
            _windows[1].Close &gt; _windows[1].Open &amp;&amp;
            _windows[0].Close &gt; _windows[0].Open &amp;&amp;
            _windows[0].Close &gt; _windows[1].Close &amp;&amp; _windows[1].Close &gt; _windows[2].Close &amp;&amp;
            _windows[0].Volume &gt; _windows[1].Volume &amp;&amp; _windows[1].Volume &gt; _windows[2].Volume &amp;&amp;
            _windows[2].Close - _windows[2].Open &gt; _windows[1].Close - _windows[1].Open &amp;&amp;
            _windows[1].Close - _windows[1].Open &gt; _windows[0].Close - _windows[0].Open;
    }

    private bool BreakoutAction(decimal currentPrice)
    {
        // We trade breakout from contraction: the breakout should be much above the contracted range of the last bar.
        return currentPrice - _windows[0].Close &gt; (_windows[0].Close - _windows[0].Open) * 2m;
    }

    public override void OnOrderEvent(OrderEvent orderEvent)
    {
        if (orderEvent.Status == OrderStatus.Filled)
        {
            if (orderEvent.Ticket.OrderType == OrderType.Market)
            {
                // Stop loss order at 1%.
                var stopPrice = orderEvent.FillQuantity &gt; 0m ? orderEvent.FillPrice * 0.99m : orderEvent.FillPrice * 1.01m;
                StopMarketOrder(_spy, -Portfolio[_spy].Quantity, stopPrice);
                // Take profit order at 2%.
                var takeProfitPrice = orderEvent.FillQuantity &gt; 0m ? orderEvent.FillPrice * 1.02m : orderEvent.FillPrice * 0.98m;
                LimitOrder(_spy, -Portfolio[_spy].Quantity, takeProfitPrice);
            }
            else if (orderEvent.Ticket.OrderType == OrderType.StopMarket || orderEvent.Ticket.OrderType == OrderType.Limit)
            {
                // Cancel any open order if stop loss or take profit order filled.
                Transactions.CancelOpenOrders();
            }
        }
    }
}</pre>
    <pre class="python">class RollingWindowAlgorithm(QCAlgorithm):
    def initialize(self) -&gt; None:
        self.set_start_date(2021, 1, 1)
        self.set_end_date(2022, 1, 1)
        
        # Request SPY data for signal generation and trading.
        self.spy = self.add_equity("SPY", Resolution.MINUTE).symbol

        # Set up a rolling window to hold the last 3 trade bars for price action detection as trade signal.
        self.windows = RollingWindow[TradeBar](3)
        # Warm up for the rolling window.
        history = self.history[TradeBar](self.spy, 3, Resolution.MINUTE)
        for bar in history:
            self.windows.add(bar)

    def on_data(self, slice: Slice) -&gt; None:
        bar = slice.bars.get(self.spy)
        if bar:
            # Trade the price action if the previous bars fulfill a contraction breakout.
            if self.contraction_action and self.breakout(bar.close):
                self.set_holdings(self.spy, 0.5)

            # update the window with the current bar.
            self.windows.add(bar)
    
    def contraction_action(self) -&gt; None:
        # We trade contraction type price action, where the buying preesure is increasing.
        # 1. The last 3 bars are green.
        # 2. The price is increasing in trend.
        # 3. The trading volume is increasing as well.
        # 4. The range of the bars are decreasing.
        return self.windows[2].close &gt; self.windows[2].open and \
            self.windows[1].close &gt; self.windows[1].open and \
            self.windows[0].close &gt; self.windows[0].open and \
            self.windows[0].close &gt; self.windows[1].close &gt; self.windows[2].close and \
            self.windows[0].volume &gt; self.windows[1].volume &gt; self.windows[2].volume and \
            self.windows[2].close - self.windows[2].open &gt; self.windows[1].close - self.windows[1].open &gt; self.windows[0].close - self.windows[0].open
    
    def breakout(self, current_close: float) -&gt; None:
        # We trade breakout from contraction: the breakout should be much above the contracted range of the last bar.
        return current_close - self.windows[0].close &gt; (self.windows[0].close - self.windows[0].open) * 2

    def on_order_event(self, order_event: OrderEvent) -&gt; None:
        if order_event.status == OrderStatus.FILLED:
            if order_event.ticket.order_type == OrderType.MARKET:
                # Stop loss order at 1%.
                stop_price = order_event.fill_price * (0.99 if order_event.fill_quantity &gt; 0 else 1.01)
                self.stop_market_order(self.spy, -self.portfolio[self.spy].quantity, stop_price)
                # Take profit order at 2%.
                take_profit_price = order_event.fill_price * (1.02 if order_event.fill_quantity &gt; 0 else 0.98)
                self.limit_order(self.spy, -self.portfolio[self.spy].quantity, take_profit_price)
            elif order_event.ticket.order_type == OrderType.STOP_MARKET or order_event.ticket.order_type == OrderType.LIMIT:
                # Cancel any open order if stop loss or take profit order filled.
                self.transactions.cancel_open_orders()</pre>
</div>

<h4>Example 2: Bid-Ask Spread</h4>
<p>The following algorithm trades the microeconomy of SPY's supply-demand relationship. We buy SPY if the current bid-ask spread is less than average of that of the last 20 quote bars, indicating demand is approaching supply, while short SPY vice versa. To save the last 20 quote data, we use a rolling window and save the calculated spread.</p>
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

            // Buy if the current spread is smaller than average, indicating demand is approaching supply. Buy force will drive up price.
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

            # Buy if the current spread is smaller than average, indicating demand is approaching supply. Buy force will drive up price.
            if spread &lt; np.mean(list(self.windows)):
                self.set_holdings(self.spy, -0.5)
            # Short if the current spread is larger than average, indicating supply is gradually overwhelming demand.
            elif spread &gt; np.mean(list(self.windows)):
                self.set_holdings(self.spy, 0.5)</pre>
</div>

<h4>Example 3: Bid-Ask Spread</h4>
<p>The following algorithm trades the microeconomy of SPY's supply-demand relationship. We buy SPY if the current bid-ask spread is less than average of that of the last 20 quote bars, indicating demand is approaching supply, while short SPY vice versa. To save the last 20 quote data, we use a rolling window and save the calculated spread.</p>
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

<h4>Other Examples</h4>
<p>For more examples, see the following algorithms:</p>
<? include(DOCS_RESOURCES."/rolling-window/examples.html"); ?>