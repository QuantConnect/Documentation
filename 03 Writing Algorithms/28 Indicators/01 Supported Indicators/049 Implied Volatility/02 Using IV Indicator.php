<p>To create an automatic indicators for <code>ImpliedVolatility</code>, call the <code class='csharp'>IV</code><code class='python'>iv</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>IV</code><code class='python'>iv</code> method creates a <code>ImpliedVolatility</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
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
    }

    public override void OnData(Slice data)
    {
        if (_iv.IsReady)
        {
            // The current value of _iv is represented by itself (_iv)
            // or _iv.Current.Value
            Plot("ImpliedVolatility", "iv", _iv);
        }
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

    def on_data(self, slice: Slice) -> None:
        if self._iv.is_ready:
            # The current value of self._iv is represented by self._iv.current.value
            self.plot("ImpliedVolatility", "iv", self._iv.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.iv">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>ImpliedVolatility</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class ImpliedVolatilityAlgorithm : QCAlgorithm
{
    private Symbol _symbol, _option, _mirrorOption;
    private ImpliedVolatility _impliedvolatility;

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

        _impliedvolatility = new ImpliedVolatility(_option, interestRateModel, dividendYieldModel, _mirrorOption);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _impliedvolatility.Update(new IndicatorDataPoint(_symbol, bar.EndTime, bar.Close));
        if (data.QuoteBars.TryGetValue(_option, out var quoteBar))
            _impliedvolatility.Update(new IndicatorDataPoint(_option, quoteBar.EndTime, quoteBar.Close));
        if (data.QuoteBars.TryGetValue(_mirrorOption, out quoteBar))
            _impliedvolatility.Update(new IndicatorDataPoint(_mirrorOption, quoteBar.EndTime, quoteBar.Close));

        if (_impliedvolatility.IsReady)
        {
            // The current value of _impliedvolatility is represented by itself (_impliedvolatility)
            // or _impliedvolatility.Current.Value
            Plot("ImpliedVolatility", "impliedvolatility", _impliedvolatility);
        }
    }
}</pre>
<pre class="python">class ImpliedVolatilityAlgorithm(QCAlgorithm):
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

        self._impliedvolatility = ImpliedVolatility(self._option, interest_rate_model, dividend_yield_model, self._mirror_option)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._impliedvolatility.update(IndicatorDataPoint(self._symbol, bar.end_time, bar.close))
        bar = slice.quote_bars.get(self._option)
        if bar:
            self._impliedvolatility.update(IndicatorDataPoint(self._option, bar.end_time, bar.close))
        bar = slice.quote_bars.get(self._mirror_option)
        if bar:
            self._impliedvolatility.update(IndicatorDataPoint(self._mirror_option, bar.end_time, bar.close))

        if self._impliedvolatility.is_ready:
            # The current value of self._impliedvolatility is represented by self._impliedvolatility.current.value
            self.plot("ImpliedVolatility", "impliedvolatility", self._impliedvolatility.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1ImpliedVolatility.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/ImpliedVolatility">reference</a>.</p>