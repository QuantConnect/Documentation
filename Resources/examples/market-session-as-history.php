<h4>Example <?=$number?>: Market Session as History</h4>
<p>Using <code>Session</code> instead of <a href="/docs/v2/writing-algorithms/historical-data/history-requests">history requests</a> or <a href="/docs/v2/writing-algorithms/historical-data/rolling-window">rolling window</a> to cache the last 252 OHLCV bars, the following algorithm buys SPY accordingly when the market opens above the 252-day max of closing prices.</p>
<div class="section-example-container testable">
    <pre class="csharp">public class MarketSessionAsDailyHistory : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 12, 31);
        var equity = AddEquity("SPY");

        // Increase the session lookback to 252 from default (2) 
        // since our strategy break out the 252-day max of closing prices
        equity.Session.Size = 252;
        // Warm up with daily data for speed
        SetWarmUp(equity.Session.Size, Resolution.Daily);

        Schedule.On(DateRules.EveryDay("SPY"), TimeRules.AfterMarketOpen("SPY", 1), () =&gt;
        {
            // Break-out strategy. If the market opens above the 252-day max of closing prices, 
            // allocate all in SPY. Liquidate if it opens below the mean. 
            
            var session = Securities["SPY"].Session;
            // The session is ready when the number of samples is greater than the size (252)
            if (IsWarmingUp || !session.IsReady)
                return;

            var closes = session.OrderBy(x =&gt; x.EndTime).Select(x =&gt; x.Close);
            var open = session.Open;
            var max = closes.Max();
            var mean = closes.Average();

            // Invest all in SPY if the open break out the 252-day max and liquidate below the mean
            if (open &gt; max)
                SetHoldings("SPY", 1, tag: $"_open={open:F2}, _max={max:F2}");
            if (open &lt; mean)
                Liquidate(symbol: "SPY", tag:$"_open={open:F2}, _mean={mean:F2}");
        });
    }
}</pre>
    <pre class="python">class MarketSessionAsDailyHistory(QCAlgorithm):
    def initialize(self):
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 12, 31)
        equity = self.add_equity("SPY")

        # Increase the session lookback to 252 from default (2) 
        # since our strategy break out the 252-day max of closing prices
        equity.session.size = 252
        # Warm up with daily data for speed
        self.set_warm_up(equity.session.size, Resolution.DAILY)

        self.schedule.on(self.date_rules.every_day("SPY"), self.time_rules.after_market_open("SPY", 1), self.trade_break_out)

    def trade_break_out(self):
        '''Break-out strategy. If the market opens above the 252-day max of closing prices, 
        allocate all in SPY. Liquidate if it opens below the mean. 
        '''
        session = self.securities["SPY"].session
        # The session is ready when the number of samples is greater than the size (252)
        if self.is_warming_up or not session.is_ready:
            return

        # Create a pandas Series of the closing prices of session 
        # to use the max and mean methods
        closes = pd.Series([x.close for x in session][::-1])
        _open, _max, _mean = session.open, closes.max(), closes.mean()

        # Invest all in SPY if the open break out the 252-day max and liquidate below the mean
        if _open &gt; _max:
            self.set_holdings("SPY", 1, tag=f'{_open=:.2f}, {_max=:.2f}')
        if _open &lt; _mean:
            self.liquidate("SPY", tag=f'{_open=:.2f}, {_mean=:.2f}')
    </pre>
</div>
