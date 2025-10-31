<p>To create an automatic indicators for <code>Delta</code>, call the <code class='csharp'>D</code><code class='python'>d</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>D</code><code class='python'>d</code> method creates a <code>Delta</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class DeltaAlgorithm : QCAlgorithm
{
    private Symbol _symbol, _option, _mirrorOption;
    private Delta _d;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 9, 20);

        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _option = SymbolRepresentation.ParseOptionTickerOSI("SPY 240920C00564000");
        _mirrorOption = SymbolRepresentation.ParseOptionTickerOSI("SPY 240920P00564000");
        AddOptionContract(_option, Resolution.Daily);
        AddOptionContract(_mirrorOption, Resolution.Daily);

        _d = D(_option, _mirrorOption);
    }

    public override void OnData(Slice data)
    {
        if (_d.IsReady)
        {
            // The current value of _d is represented by itself (_d)
            // or _d.Current.Value
            Plot("Delta", "d", _d);
        }
    }
}</pre>
<pre class="python">class DeltaAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 9, 20)

        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._option = SymbolRepresentation.parse_option_ticker_osi("SPY 240920C00564000");
        self._mirror_option = SymbolRepresentation.parse_option_ticker_osi("SPY 240920P00564000");
        self.add_option_contract(self._option, Resolution.DAILY);
        self.add_option_contract(self._mirror_option, Resolution.DAILY);
        self._d = self.d(self._option, self._mirror_option)

    def on_data(self, slice: Slice) -> None:
        if self._d.is_ready:
            # The current value of self._d is represented by self._d.current.value
            self.plot("Delta", "d", self._d.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.d">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>Delta</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class DeltaAlgorithm : QCAlgorithm
{
    private Symbol _symbol, _option, _mirrorOption;
    private Delta _delta;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 9, 20);

        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        var interestRateModel = new InterestRateProvider();
        var dividendYieldModel = new DividendYieldProvider(_symbol);
        
        _option = SymbolRepresentation.ParseOptionTickerOSI("SPY 240920C00564000");
        _mirrorOption = SymbolRepresentation.ParseOptionTickerOSI("SPY 240920P00564000");
        AddOptionContract(_option, Resolution.Daily);
        AddOptionContract(_mirrorOption, Resolution.Daily);

        _delta = new Delta(_option, interestRateModel, dividendYieldModel, _mirrorOption);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _delta.Update(new IndicatorDataPoint(_symbol, bar.EndTime, bar.Close));
        if (data.QuoteBars.TryGetValue(_option, out var quoteBar))
            _delta.Update(new IndicatorDataPoint(_option, quoteBar.EndTime, quoteBar.Close));
        if (data.QuoteBars.TryGetValue(_mirrorOption, out quoteBar))
            _delta.Update(new IndicatorDataPoint(_mirrorOption, quoteBar.EndTime, quoteBar.Close));

        if (_delta.IsReady)
        {
            // The current value of _delta is represented by itself (_delta)
            // or _delta.Current.Value
            Plot("Delta", "delta", _delta);
        }
    }
}</pre>
<pre class="python">class DeltaAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 9, 20)

        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        interest_rate_model = InterestRateProvider()
        dividend_yield_model = DividendYieldProvider(self._symbol)

        self._option = SymbolRepresentation.parse_option_ticker_osi("SPY 240920C00564000");
        self._mirror_option = SymbolRepresentation.parse_option_ticker_osi("SPY 240920P00564000");
        self.add_option_contract(self._option, Resolution.DAILY);
        self.add_option_contract(self._mirror_option, Resolution.DAILY);
        self._delta = Delta(self._option, interest_rate_model, dividend_yield_model, self._mirror_option)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._delta.update(IndicatorDataPoint(self._symbol, bar.end_time, bar.close))
        bar = slice.quote_bars.get(self._option)
        if bar:
            self._delta.update(IndicatorDataPoint(self._option, bar.end_time, bar.close))
        bar = slice.quote_bars.get(self._mirror_option)
        if bar:
            self._delta.update(IndicatorDataPoint(self._mirror_option, bar.end_time, bar.close))

        if self._delta.is_ready:
            # The current value of self._delta is represented by self._delta.current.value
            self.plot("Delta", "delta", self._delta.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1Delta.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/Delta">reference</a>.</p>