<h4>Example <?=$number?>: Bid-Ask Spread</h4>
<p>
    The following algorithm trades the microeconomy of SPY's supply-demand relationship. It buys when the current bid-ask spread is less than average spread over the last 20 QuoteBar objects, indicating demand is approaching supply. 
    When the spread is greater than the average, it shorts SPY. 
    To save the last spread values, it uses a <code>RollingWindow</code>.
</p>
<div class="section-example-container testable">
    <pre class="csharp">public class RollingWindowAlgorithm : QCAlgorithm
{
    private Symbol _spy;
    // Set up a rolling window to hold the last 20 bar's bid-ask spread for trade signal generation.
    private RollingWindow&lt;decimal&gt; _windows = new(20);

    public override void Initialize()
    {
        SetStartDate(2020, 2, 20);
        SetEndDate(2020, 2, 27);

        // Add SPY data for signal generation and trading.
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
        
        # Add SPY data for signal generation and trading.
        self.spy = self.add_equity("SPY", Resolution.MINUTE).symbol

        # Set up a rolling window to hold the last 20 bar's bid-ask spread for trade signal generation.
        self.windows = RollingWindow(20)
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
