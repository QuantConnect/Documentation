<p>To create an automatic indicators for <code>Doji</code>, call the <code class='csharp'>Doji</code><code class='python'>doji</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>Doji</code><code class='python'>doji</code> method creates a <code>Doji</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class DojiAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private Doji _doji;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _doji = CandlestickPatterns.Doji(_symbol);
    }

    public override void OnData(Slice data)
    {
        if (_doji.IsReady)
        {
            // The current value of _doji is represented by itself (_doji)
            // or _doji.Current.Value
            Plot("Doji", "doji", _doji);
        }
    }
}</pre>
<pre class="python">class DojiAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._doji = self.candlestick_patterns.doji(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._doji.is_ready:
            # The current value of self._doji is represented by self._doji.current.value
            self.plot("Doji", "doji", self._doji.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.doji">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>Doji</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class DojiAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private Doji _doji;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _doji = new Doji();
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _doji.Update(bar);

        if (_doji.IsReady)
        {
            // The current value of _doji is represented by itself (_doji)
            // or _doji.Current.Value
            Plot("Doji", "doji", _doji);
        }
    }
}</pre>
<pre class="python">class DojiAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._doji = Doji()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._doji.update(bar)

        if self._doji.is_ready:
            # The current value of self._doji is represented by self._doji.current.value
            self.plot("Doji", "doji", self._doji.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1Doji.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/Doji">reference</a>.</p>