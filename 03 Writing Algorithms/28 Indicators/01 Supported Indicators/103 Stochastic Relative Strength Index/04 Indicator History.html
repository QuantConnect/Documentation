<p>To get the historical data of the <code>StochasticRelativeStrengthIndex</code> indicator, call the <code class="csharp">IndicatorHistory</code><code class="python">self.indicator_history</code> method. This method resets your indicator, makes a <a href='/docs/v2/writing-algorithms/historical-data/history-requests'>history request</a>, and updates the indicator with the historical data. Just like with regular history requests, the <code class="csharp">IndicatorHistory</code><code class="python">indicator_history</code> method supports time periods based on a trailing number of bars, a trailing period of time, or a defined period of time. If you don't provide a <code>resolution</code> argument, it defaults to match the resolution of the security subscription.
</p>
<div class="section-example-container testable">
<pre class="csharp">public class StochasticRelativeStrengthIndexAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private StochasticRelativeStrengthIndex _srsi;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _srsi = SRSI(_symbol, 14, 14, 3, 3);

        var indicatorHistory = IndicatorHistory(_srsi, _symbol, 100, Resolution.Minute);
        var timeSpanIndicatorHistory = IndicatorHistory(_srsi, _symbol, TimeSpan.FromDays(10), Resolution.Minute);
        var timePeriodIndicatorHistory = IndicatorHistory(_srsi, _symbol, new DateTime(2024, 7, 1), new DateTime(2024, 7, 5), Resolution.Minute);

        // Access all attributes of indicatorHistory
        var k = indicatorHistory.Select(x => ((dynamic)x).K).ToList();
        var d = indicatorHistory.Select(x => ((dynamic)x).D).ToList();
    }
}</pre>
<pre class="python">class StochasticRelativeStrengthIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._srsi = self.srsi(self._symbol, 14, 14, 3, 3)

        indicator_history = self.indicator_history(self._srsi, self._symbol, 100, Resolution.MINUTE)
        timedelta_indicator_history = self.indicator_history(self._srsi, self._symbol, timedelta(days=10), Resolution.MINUTE)
        time_period_indicator_history = self.indicator_history(self._srsi, self._symbol, datetime(2024, 7, 1), datetime(2024, 7, 5), Resolution.MINUTE)
    
        # Access all attributes of indicator_history
        indicator_history_df = indicator_history.data_frame
        k = indicator_history_df["k"]
        d = indicator_history_df["d"]</pre></div>