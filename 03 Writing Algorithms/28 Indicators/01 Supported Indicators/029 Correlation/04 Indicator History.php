<p>To get the historical data of the <code>Correlation</code> indicator, call the <code class="csharp">IndicatorHistory</code><code class="python">self.indicator_history</code> method. This method resets your indicator, makes a <a href='/docs/v2/writing-algorithms/historical-data/history-requests'>history request</a>, and updates the indicator with the historical data. Just like with regular history requests, the <code class="csharp">IndicatorHistory</code><code class="python">indicator_history</code> method supports time periods based on a trailing number of bars, a trailing period of time, or a defined period of time. If you don't provide a <code>resolution</code> argument, it defaults to match the resolution of the security subscription.
</p>
<div class="section-example-container testable">
<pre class="csharp">public class CorrelationAlgorithm : QCAlgorithm
{
    private Symbol _symbol,_reference;
    private Correlation _c;

    public override void Initialize()
    {
        _symbol = AddEquity("QQQ", Resolution.Daily).Symbol;
        _reference = AddEquity("SPY", Resolution.Daily).Symbol;
        _c = C(_symbol, _reference, 20, CorrelationType.Pearson);

        var indicatorHistory = IndicatorHistory(_c, new[] { _symbol, _reference }, 100, Resolution.Minute);
        var timeSpanIndicatorHistory = IndicatorHistory(_c, new[] { _symbol, _reference }, TimeSpan.FromDays(10), Resolution.Minute);
        var timePeriodIndicatorHistory = IndicatorHistory(_c, new[] { _symbol, _reference }, new DateTime(2024, 7, 1), new DateTime(2024, 7, 5), Resolution.Minute);
    }
}</pre>
<pre class="python">class CorrelationAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("QQQ", Resolution.DAILY).symbol
        self._reference = self.add_equity("SPY", Resolution.DAILY).symbol
        self._c = self.c(self._symbol, self._reference, 20, CorrelationType.PEARSON)

        indicator_history = self.indicator_history(self._c, [ self._symbol, self._reference ], 100, Resolution.MINUTE)
        timedelta_indicator_history = self.indicator_history(self._c, [ self._symbol, self._reference ], timedelta(days=10), Resolution.MINUTE)
        time_period_indicator_history = self.indicator_history(self._c, [ self._symbol, self._reference ], datetime(2024, 7, 1), datetime(2024, 7, 5), Resolution.MINUTE)
    </pre></div>