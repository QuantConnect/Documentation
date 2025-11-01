<p>To get the historical data of the <code>InvertedHammer</code> indicator, call the <code class="csharp">IndicatorHistory</code><code class="python">self.indicator_history</code> method. This method resets your indicator, makes a <a href='/docs/v2/writing-algorithms/historical-data/history-requests'>history request</a>, and updates the indicator with the historical data. Just like with regular history requests, the <code class="csharp">IndicatorHistory</code><code class="python">indicator_history</code> method supports time periods based on a trailing number of bars, a trailing period of time, or a defined period of time. If you don't provide a <code>resolution</code> argument, it defaults to match the resolution of the security subscription.
</p>
<div class="section-example-container testable">
<pre class="csharp">public class InvertedHammerAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private InvertedHammer _invertedHammer;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _invertedHammer = CandlestickPatterns.InvertedHammer(_symbol);

        var indicatorHistory = IndicatorHistory(_invertedHammer, _symbol, 100, Resolution.Minute);
        var timeSpanIndicatorHistory = IndicatorHistory(_invertedHammer, _symbol, TimeSpan.FromDays(10), Resolution.Minute);
        var timePeriodIndicatorHistory = IndicatorHistory(_invertedHammer, _symbol, new DateTime(2024, 7, 1), new DateTime(2024, 7, 5), Resolution.Minute);
    }
}</pre>
<pre class="python">class InvertedHammerAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._inverted_hammer = self.candlestick_patterns.inverted_hammer(self._symbol)

        indicator_history = self.indicator_history(_inverted_hammer, self._symbol, 100, Resolution.MINUTE)
        timedelta_indicator_history = self.indicator_history(_inverted_hammer, self._symbol, timedelta(days=10), Resolution.MINUTE)
        time_period_indicator_history = self.indicator_history(_inverted_hammer, self._symbol, datetime(2024, 7, 1), datetime(2024, 7, 5), Resolution.MINUTE)</pre></div>