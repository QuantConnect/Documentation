<p>To get the historical data of the <code>PercentagePriceOscillator</code> indicator, call the <code class="csharp">IndicatorHistory</code><code class="python">self.indicator_history</code> method. This method resets your indicator, makes a <a href='/docs/v2/writing-algorithms/historical-data/history-requests'>history request</a>, and updates the indicator with the historical data. Just like with regular history requests, the <code class="csharp">IndicatorHistory</code><code class="python">indicator_history</code> method supports time periods based on a trailing number of bars, a trailing period of time, or a defined period of time. If you don't provide a <code>resolution</code> argument, it defaults to match the resolution of the security subscription.
</p>
<div class="section-example-container testable">
<pre class="csharp">public class PercentagePriceOscillatorAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private PercentagePriceOscillator _ppo;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _ppo = PPO(_symbol, 10, 20, MovingAverageType.Simple);

        var indicatorHistory = IndicatorHistory(_ppo, _symbol, 100, Resolution.Minute);
        var timeSpanIndicatorHistory = IndicatorHistory(_ppo, _symbol, TimeSpan.FromDays(10), Resolution.Minute);
        var timePeriodIndicatorHistory = IndicatorHistory(_ppo, _symbol, new DateTime(2024, 7, 1), new DateTime(2024, 7, 5), Resolution.Minute);

        // Access all attributes of indicatorHistory
        var fast = indicatorHistory.Select(x => ((dynamic)x).Fast).ToList();
        var slow = indicatorHistory.Select(x => ((dynamic)x).Slow).ToList();
        var signal = indicatorHistory.Select(x => ((dynamic)x).Signal).ToList();
        var histogram = indicatorHistory.Select(x => ((dynamic)x).Histogram).ToList();
    }
}</pre>
<pre class="python">class PercentagePriceOscillatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._ppo = self.ppo(self._symbol, 10, 20, MovingAverageType.SIMPLE)

        indicator_history = self.indicator_history(self._ppo, self._symbol, 100, Resolution.MINUTE)
        timedelta_indicator_history = self.indicator_history(self._ppo, self._symbol, timedelta(days=10), Resolution.MINUTE)
        time_period_indicator_history = self.indicator_history(self._ppo, self._symbol, datetime(2024, 7, 1), datetime(2024, 7, 5), Resolution.MINUTE)
    
        # Access all attributes of indicator_history
        indicator_history_df = indicator_history.data_frame
        fast = indicator_history_df["fast"]
        slow = indicator_history_df["slow"]
        signal = indicator_history_df["signal"]
        histogram = indicator_history_df["histogram"]</pre></div>