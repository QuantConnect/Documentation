<h4>Example <?=$number?>: On Events And Trading Logic</h4>
<p>The following example trades a simple EMA cross trend-following strategy on SPY. We log or debug messages in event handlers and trading logic changes to get information on any event flow.</p>
<div class="section-example-container" id="testable">
    <pre class="csharp">public class LoggingAlgorithm : QCAlgorithm
{
    private Symbol _spy;
    private ExponentialMovingAverage _ema;

    public override void Initialize()
    {
        SetStartDate(2024, 8, 12);
        SetEndDate(2024, 9, 1);
        SetCash(1000000);

        // Request SPY data to trade.
        _spy = AddEquity("SPY").Symbol;
        // Create an EMA indicator to generate trade signals.
        _ema = EMA(_spy, 20, Resolution.Daily);

        // Warm up the algorithm for indicator readiness.
        SetWarmUp(20, Resolution.Daily);
    }

    public override void OnWarmupFinished()
    {
        // Signals on warm-up finished.
        Log("Warm up finished");
    }

    public override void OnData(Slice slice)
    {
        if (!IsWarmingUp &amp;&amp; slice.Bars.TryGetValue(_spy, out var bar))
        {
            // Trend-following strategy using price and EMA.
            // If the price is above EMA, SPY is in an uptrend, and we buy it.
            if (bar.Close &gt; _ema &amp;&amp; Portfolio[_spy].IsLong)
            {
                Debug("Trend changes to upwards");
                SetHoldings(_spy, 1m);
            }
            else if (bar.Close &lt; _ema &amp;&amp; Portfolio[_spy].IsShort)
            {
                Debug("Trend changes to downwards");
                SetHoldings(_spy, -1m);
            }
        }
    }

    public override void OnOrderEvent(OrderEvent orderEvent)
    {
        if (orderEvent.Status == OrderStatus.Filled)
        {
            // Log the order details if being filled.
            Log($"Order filled - Quantity: {orderEvent.Quantity}");
        }
    }
}</pre>
    <pre class="python">class LoggingAlgorithm(QCAlgorithm):
    def initialize(self) -&gt; None:
        self.set_start_date(2024, 8, 12)
        self.set_end_date(2024, 9, 1)
        self.set_cash(1000000)

        # Request SPY data to trade.
        self.spy = self.add_equity("SPY").symbol
        # Create an EMA indicator to generate trade signals.
        self._ema = self.ema(self.spy, 20, Resolution.DAILY)

        # Warm up the algorithm for indicator readiness.
        self.set_warm_up(20, Resolution.DAILY)

    def on_warmup_finished(self) -&gt; None:
        # Signals on warm-up finished.
        self.log("Warm up finished")

    def on_data(self, slice: Slice) -&gt; None:
        bar = None if self.is_warming_up else slice.bars.get(self.spy)
        if bar:
            # Trend-following strategy using price and EMA.
            # If the price is above EMA, SPY is in an uptrend, and we buy it.
            if bar.close &gt; self._ema.current.value and not self.portfolio[self.spy].is_long:
                self.debug("Trend changes to upwards")
                self.set_holdings(self.spy, 1)
            elif bar.close &lt; self._ema.current.value and not self.portfolio[self.spy].is_short:
                self.debug("Trend changes to downwards")
                self.set_holdings(self.spy, -1)
    
    def on_order_event(self, order_event: OrderEvent) -&gt; None:
        if order_event.status == OrderStatus.FILLED:
            # Log the order details if being filled.
            self.log(f"Order filled - Quantity: {order_event.quantity}")</pre>
</div>
