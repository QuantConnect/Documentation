<h4>Example <?=$number?>: Price Actions</h4>
<p>
    The following algorithm saves the trailing 3 <code>TradeBar</code> objects into a <code>RollingWindow</code>.
    When it identifies a volume contraction breakout price action pattern on the SPY, it buys to ride on the capital inflow. 
    To exit positions, it places a 2% take profit and 1% stop loss order in the <code class="csharp">OnOrderEvent</code><code class="python">on_order_event</code> method.
</p>
<div class="section-example-container">
    <pre class="csharp">public class RollingWindowAlgorithm : QCAlgorithm
{
    private Symbol _spy;
    // Set up a rolling window to hold the last 3 trade bars for price action detection as the trade signal.
    private RollingWindow&lt;TradeBar&gt; _windows = new(3);

    public override void Initialize()
    {
        SetStartDate(2021, 1, 1);
        SetEndDate(2022, 1, 1);

        // Add SPY data for signal generation and trading.
        _spy = AddEquity("SPY", Resolution.Minute).Symbol;

        // Warm up the rolling window.
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

            // Add the current bar to the window.
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
        // Trade breakout from contraction: the breakout should be much greater than the contracted range of the last bar.
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
                // Cancel open orders if the stop loss or take profit order fills.
                Transactions.CancelOpenOrders();
            }
        }
    }
}</pre>
    <pre class="python">class RollingWindowAlgorithm(QCAlgorithm):
    def initialize(self) -&gt; None:
        self.set_start_date(2021, 1, 1)
        self.set_end_date(2022, 1, 1)

        # Add SPY data for signal generation and trading.
        self.spy = self.add_equity("SPY", Resolution.MINUTE).symbol

        # Set up a rolling window to hold the last 3 trade bars for price action detection as the trade signal.
        self.windows = RollingWindow[TradeBar](3)
        # Warm up the rolling window.
        history = self.history[TradeBar](self.spy, 3, Resolution.MINUTE)
        for bar in history:
            self.windows.add(bar)

    def on_data(self, slice: Slice) -&gt; None:
        bar = slice.bars.get(self.spy)
        if bar:
            # Trade the price action if the previous bars fulfill a contraction breakout.
            if self.contraction_action and self.breakout(bar.close):
                self.set_holdings(self.spy, 0.5)

            # Add the current bar to the window.
            self.windows.add(bar)

    def contraction_action(self) -&gt; None:
        # We trade contraction type price action, where the buying preesure is increasing.
        # 1. The last 3 bars are green.
        # 2. The price is increasing in trend.
        # 3. The trading volume is increasing as well.
        # 4. The range of the bars are decreasing.
        return (
            self.windows[2].close &gt; self.windows[2].open and
            self.windows[1].close &gt; self.windows[1].open and
            self.windows[0].close &gt; self.windows[0].open and
            self.windows[0].close &gt; self.windows[1].close &gt; self.windows[2].close and
            self.windows[0].volume &gt; self.windows[1].volume &gt; self.windows[2].volume and
            self.windows[2].close - self.windows[2].open &gt; self.windows[1].close - self.windows[1].open &gt; self.windows[0].close - self.windows[0].open
       )

    def breakout(self, current_close: float) -&gt; None:
        # Trade breakout from contraction: the breakout should be much greater than the contracted range of the last bar.
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
                # Cancel open orders if stop loss or take profit order fills.
                self.transactions.cancel_open_orders()</pre>
</div>
