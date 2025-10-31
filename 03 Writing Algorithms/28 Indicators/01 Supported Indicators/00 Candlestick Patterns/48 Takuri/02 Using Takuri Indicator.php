<p>To create an automatic indicators for <code>Takuri</code>, call the <code class='csharp'>Takuri</code><code class='python'>takuri</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>Takuri</code><code class='python'>takuri</code> method creates a <code>Takuri</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class TakuriAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private Takuri _takuri;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _takuri = CandlestickPatterns.Takuri(_symbol);
    }

    public override void OnData(Slice data)
    {
        if (_takuri.IsReady)
        {
            // The current value of _takuri is represented by itself (_takuri)
            // or _takuri.Current.Value
            Plot("Takuri", "takuri", _takuri);
        }
    }
}</pre>
<pre class="python">class TakuriAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._takuri = self.candlestick_patterns.takuri(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._takuri.is_ready:
            # The current value of self._takuri is represented by self._takuri.current.value
            self.plot("Takuri", "takuri", self._takuri.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.takuri">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>Takuri</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class TakuriAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private Takuri _takuri;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _takuri = new Takuri();
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _takuri.Update(bar);

        if (_takuri.IsReady)
        {
            // The current value of _takuri is represented by itself (_takuri)
            // or _takuri.Current.Value
            Plot("Takuri", "takuri", _takuri);
        }
    }
}</pre>
<pre class="python">class TakuriAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._takuri = Takuri()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._takuri.update(bar)

        if self._takuri.is_ready:
            # The current value of self._takuri is represented by self._takuri.current.value
            self.plot("Takuri", "takuri", self._takuri.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1Takuri.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/Takuri">reference</a>.</p>