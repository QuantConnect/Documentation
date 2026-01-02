<p>To get the historical data of the <code>ImpliedVolatility</code> indicator, call the <code class="csharp">IndicatorHistory</code><code class="python">self.indicator_history</code> method. This method resets your indicator, makes a <a href='/docs/v2/writing-algorithms/historical-data/history-requests'>history request</a>, and updates the indicator with the historical data. Just like with regular history requests, the <code class="csharp">IndicatorHistory</code><code class="python">indicator_history</code> method supports time periods based on a trailing number of bars, a trailing period of time, or a defined period of time. If you don't provide a <code>resolution</code> argument, it defaults to match the resolution of the security subscription.
</p>
<div class="section-example-container testable">
<pre class="csharp">public class ImpliedVolatilityAlgorithm : QCAlgorithm
{
    private Symbol _symbol, _option, _mirrorOption;
    private ImpliedVolatility _iv;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 9, 20);

        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _option = SymbolRepresentation.ParseOptionTickerOSI("SPY 240920C00564000");
        _mirrorOption = SymbolRepresentation.ParseOptionTickerOSI("SPY 240920P00564000");
        AddOptionContract(_option, Resolution.Daily);
        AddOptionContract(_mirrorOption, Resolution.Daily);

        _iv = IV(_option, _mirrorOption);

        var indicatorHistory = IndicatorHistory(_iv, new[] { _symbol, _option, _mirrorOption }, 100, Resolution.Minute);
        var timeSpanIndicatorHistory = IndicatorHistory(_iv, new[] { _symbol, _option, _mirrorOption }, TimeSpan.FromDays(10), Resolution.Minute);
        var timePeriodIndicatorHistory = IndicatorHistory(_iv, new[] { _symbol, _option, _mirrorOption }, new DateTime(2024, 7, 1), new DateTime(2024, 7, 5), Resolution.Minute);
    }
}</pre>
<pre class="python">class ImpliedVolatilityAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 9, 20)

        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._option = SymbolRepresentation.parse_option_ticker_osi("SPY 240920C00564000");
        self._mirror_option = SymbolRepresentation.parse_option_ticker_osi("SPY 240920P00564000");
        self.add_option_contract(self._option, Resolution.DAILY);
        self.add_option_contract(self._mirror_option, Resolution.DAILY);

        self._iv = self.iv(self._option, self._mirror_option)

        indicator_history = self.indicator_history(self._iv, [self._symbol, self._option, self._mirror_option], 100, Resolution.MINUTE)
        timedelta_indicator_history = self.indicator_history(self._iv, [self._symbol, self._option, self._mirror_option], timedelta(days=10), Resolution.MINUTE)
        time_period_indicator_history = self.indicator_history(self._iv, [self._symbol, self._option, self._mirror_option], datetime(2024, 7, 1), datetime(2024, 7, 5), Resolution.MINUTE)</pre></div>