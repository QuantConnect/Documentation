<p>To get the historical data of the <code>CommodityChannelIndex</code> indicator, call the <code class="csharp">IndicatorHistory</code><code class="python">self.indicator_history</code> method. This method resets your indicator, makes a <a href='/docs/v2/writing-algorithms/historical-data/history-requests'>history request</a>, and updates the indicator with the historical data. Just like with regular history requests, the <code class="csharp">IndicatorHistory</code><code class="python">indicator_history</code> method supports time periods based on a trailing number of bars, a trailing period of time, or a defined period of time. If you don't provide a <code>resolution</code> argument, it defaults to match the resolution of the security subscription.
</p>
<div class="section-example-container testable">
<pre class="csharp">public class CommodityChannelIndexAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private CommodityChannelIndex _cci;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _cci = CCI(_symbol, 20, MovingAverageType.Simple);

        var indicatorHistory = IndicatorHistory(_cci, _symbol, 100, Resolution.Minute);
        var timeSpanIndicatorHistory = IndicatorHistory(_cci, _symbol, TimeSpan.FromDays(10), Resolution.Minute);
        var timePeriodIndicatorHistory = IndicatorHistory(_cci, _symbol, new DateTime(2024, 7, 1), new DateTime(2024, 7, 5), Resolution.Minute);

        // Access all attributes of indicatorHistory
        var typicalPriceAverage = indicatorHistory.Select(x => ((dynamic)x).TypicalPriceAverage).ToList();
        var typicalPriceMeanDeviation = indicatorHistory.Select(x => ((dynamic)x).TypicalPriceMeanDeviation).ToList();
    }
}</pre>
<pre class="python">class CommodityChannelIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._cci = self.cci(self._symbol, 20, MovingAverageType.SIMPLE)

        indicator_history = self.indicator_history(self._cci, self._symbol, 100, Resolution.MINUTE)
        timedelta_indicator_history = self.indicator_history(self._cci, self._symbol, timedelta(days=10), Resolution.MINUTE)
        time_period_indicator_history = self.indicator_history(self._cci, self._symbol, datetime(2024, 7, 1), datetime(2024, 7, 5), Resolution.MINUTE)
    
        # Access all attributes of indicator_history
        indicator_history_df = indicator_history.data_frame
        typical_price_average = indicator_history_df["typical_price_average"]
        typical_price_mean_deviation = indicator_history_df["typical_price_mean_deviation"]</pre></div>