<p>
  To get historical <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices'>Slice</a> data, call the <code class='csharp'>History</code><code class='python'>history</code> method without passing any <code>Symbol</code> objects.
  This method returns <code>Slice</code> objects, which contain data points from all the datasets in your algorithm.
  If you omit the <code>resolution</code> argument, it uses the resolution that you set for each security and dataset when you created the subscriptions.
</p>

<div class="section-example-container">
    <pre class="csharp">public class SliceHistoryAlgorithm : QCAlgorithm
{
    public override void Initialize()
    {
        SetStartDate(2024, 12, 19);
        // Add some securities and datasets.
        var future = AddFuture(Futures.Indices.SP500EMini);
        future.SetFilter(universe => universe.FrontMonth());
        AddFutureOption(future.Symbol, universe => universe.FrontMonth().Strikes(-1, 0).CallsOnly());
        // Add a Scheduled Event that runs at the start of each month.
        Schedule.On(DateRules.MonthStart(future.Symbol), TimeRules.AfterMarketOpen(future.Symbol, 60), Trade);
    }

    public void Trade()
    {
        // Get the historical Slice objects over the last 30 minutes for all the subcriptions in your algorithm.
        var history = History(30, Resolution.Minute);
        // Iterate through each historical Slice.
        foreach (var slice in history)
        {
            // Iterate through each TradeBar in this Slice.
            foreach (var kvp in slice.Bars)
            {
                var symbol = kvp.Key;
                var bar = kvp.Value;
            }
        }
    }
}</pre>
    <pre class="python">class SliceHistoryAlgorithm(QCAlgorithm):

    def initialize(self) -> None:
        self.set_start_date(2024, 12, 19)
        # Add some securities and datasets.
        future = self.add_future(Futures.Indices.SP_500_E_MINI)
        future.set_filter(lambda universe: universe.front_month())
        self.add_future_option(future.symbol, lambda universe: universe.front_month().strikes(-1, 0).calls_only())
        # Add a Scheduled Event that runs at the start of each month.
        self.schedule.on(self.date_rules.month_start(future.symbol), self.time_rules.after_market_open(future.symbol, 60), self._trade)

    def _trade(self):
        # Get the historical Slice objects over the last 30 minutes for all the subcriptions in your algorithm.
        for slice_ in self.history(30, Resolution.MINUTE):
            # Iterate through each TradeBar in this Slice.
            for symbol, trade_bar in slice_.bars.items():
                close = trade_bar.close</pre>
</div>
