<p>To get the historical data of the <code>TrueStrengthIndex</code> indicator, call the <code class="csharp">IndicatorHistory</code><code class="python">self.indicator_history</code> method. This method resets your indicator, makes a <a href='/docs/v2/writing-algorithms/historical-data/history-requests'>history request</a>, and updates the indicator with the historical data. Just like with regular history requests, the <code class="csharp">IndicatorHistory</code><code class="python">indicator_history</code> method supports time periods based on a trailing number of bars, a trailing period of time, or a defined period of time. If you don't provide a <code>resolution</code> argument, it defaults to match the resolution of the security subscription.
</p>
<div class="section-example-container testable">
<pre class="csharp">public class TrueStrengthIndexAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private TrueStrengthIndex _tsi;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _tsi = TSI(_symbol, 25, 13, 7, MovingAverageType.Exponential);

        var indicatorHistory = IndicatorHistory(_tsi, _symbol, 100, Resolution.Minute);
        var timeSpanIndicatorHistory = IndicatorHistory(_tsi, _symbol, TimeSpan.FromDays(10), Resolution.Minute);
        var timePeriodIndicatorHistory = IndicatorHistory(_tsi, _symbol, new DateTime(2024, 7, 1), new DateTime(2024, 7, 5), Resolution.Minute);

        // Access all attributes of indicatorHistory
        var signal = indicatorHistory.Select(x => ((dynamic)x).Signal).ToList();
    }
}</pre>
<pre class="python">class TrueStrengthIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._tsi = self.tsi(self._symbol, 25, 13, 7, MovingAverageType.EXPONENTIAL)

        indicator_history = self.indicator_history(self._tsi, self._symbol, 100, Resolution.MINUTE)
        timedelta_indicator_history = self.indicator_history(self._tsi, self._symbol, timedelta(days=10), Resolution.MINUTE)
        time_period_indicator_history = self.indicator_history(self._tsi, self._symbol, datetime(2024, 7, 1), datetime(2024, 7, 5), Resolution.MINUTE)
    
        # Access all attributes of indicator_history
        indicator_history_df = indicator_history.data_frame
        signal = indicator_history_df["signal"]</pre></div>