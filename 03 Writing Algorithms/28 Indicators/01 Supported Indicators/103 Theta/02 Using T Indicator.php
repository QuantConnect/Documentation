<p>To create an automatic indicators for <code>Theta</code>, call the <code class='csharp'>T</code><code class='python'>t</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>T</code><code class='python'>t</code> method creates a <code>Theta</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class ThetaAlgorithm : QCAlgorithm
{
    private Symbol _symbol, _option, _mirrorOption;
    private Theta _t;

    public override void Initialize()
    {
        SetStartDate(2024, 9, 1);
        SetEndDate(2024, 9, 20);

        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _option = SymbolRepresentation.ParseOptionTickerOSI("SPY 240920C00564000");
        _mirrorOption = SymbolRepresentation.ParseOptionTickerOSI("SPY 240920P00564000");
        AddOptionContract(_option, Resolution.Daily);
        AddOptionContract(_mirrorOption, Resolution.Daily);

        _t = T(_option, _mirrorOption);
    }

    public override void OnData(Slice data)
    {
        if (_t.IsReady)
        {
            // The current value of _t is represented by itself (_t)
            // or _t.Current.Value
            Plot("Theta", "t", _t);
        }
    }
}</pre>
<pre class="python">class ThetaAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self.set_start_date(2024, 9, 1)
        self.set_end_date(2024, 9, 20)

        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._option = SymbolRepresentation.parse_option_ticker_osi("SPY 240920C00564000");
        self._mirror_option = SymbolRepresentation.parse_option_ticker_osi("SPY 240920P00564000");
        self.add_option_contract(self._option, Resolution.DAILY);
        self.add_option_contract(self._mirror_option, Resolution.DAILY);

        self._t = self.t(self._option, self._mirror_option)

    def on_data(self, slice: Slice) -> None:
        if self._t.is_ready:
            # The current value of self._t is represented by self._t.current.value
            self.plot("Theta", "t", self._t.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.t">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>Theta</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class ThetaAlgorithm : QCAlgorithm
{
    private Symbol _symbol, _option, _mirrorOption;
    private Theta _theta;

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

        _theta = new Theta(_option, interestRateModel, dividendYieldModel, _mirrorOption);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _theta.Update(new IndicatorDataPoint(_symbol, bar.EndTime, bar.Close));
        if (data.QuoteBars.TryGetValue(_option, out var quoteBar))
            _theta.Update(new IndicatorDataPoint(_option, quoteBar.EndTime, quoteBar.Close));
        if (data.QuoteBars.TryGetValue(_mirrorOption, out quoteBar))
            _theta.Update(new IndicatorDataPoint(_mirrorOption, quoteBar.EndTime, quoteBar.Close));

        if (_theta.IsReady)
        {
            // The current value of _theta is represented by itself (_theta)
            // or _theta.Current.Value
            Plot("Theta", "theta", _theta);
        }
    }
}</pre>
<pre class="python">class ThetaAlgorithm(QCAlgorithm):
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

        self._theta = Theta(self._option, interest_rate_model, dividend_yield_model, self._mirror_option)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._theta.update(IndicatorDataPoint(self._symbol, bar.end_time, bar.close))
        bar = slice.quote_bars.get(self._option)
        if bar:
            self._theta.update(IndicatorDataPoint(self._option, bar.end_time, bar.close))
        bar = slice.quote_bars.get(self._mirror_option)
        if bar:
            self._theta.update(IndicatorDataPoint(self._mirror_option, bar.end_time, bar.close))

        if self._theta.is_ready:
            # The current value of self._theta is represented by self._theta.current.value
            self.plot("Theta", "theta", self._theta.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1Theta.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/Theta">reference</a>.</p>