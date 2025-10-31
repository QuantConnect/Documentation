<p>To create an automatic indicators for <code>HikkakeModified</code>, call the <code class='csharp'>HikkakeModified</code><code class='python'>hikkake_modified</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>HikkakeModified</code><code class='python'>hikkake_modified</code> method creates a <code>HikkakeModified</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class HikkakeModifiedAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private HikkakeModified _hikkakeModified;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _hikkakeModified = CandlestickPatterns.HikkakeModified(_symbol);
    }

    public override void OnData(Slice data)
    {
        if (_hikkakeModified.IsReady)
        {
            // The current value of _hikkakeModified is represented by itself (_hikkakeModified)
            // or _hikkakeModified.Current.Value
            Plot("HikkakeModified", "hikkakeModified", _hikkakeModified);
        }
    }
}</pre>
<pre class="python">class HikkakeModifiedAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._hikkake_modified = self.candlestick_patterns.hikkake_modified(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._hikkake_modified.is_ready:
            # The current value of self._hikkake_modified is represented by self._hikkake_modified.current.value
            self.plot("HikkakeModified", "hikkake_modified", self._hikkake_modified.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.hikkake_modified">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>HikkakeModified</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class HikkakeModifiedAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private HikkakeModified _hikkakeModified;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _hikkakeModified = new HikkakeModified();
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _hikkakeModified.Update(bar);

        if (_hikkakeModified.IsReady)
        {
            // The current value of _hikkakeModified is represented by itself (_hikkakeModified)
            // or _hikkakeModified.Current.Value
            Plot("HikkakeModified", "hikkakeModified", _hikkakeModified);
        }
    }
}</pre>
<pre class="python">class HikkakeModifiedAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._hikkake_modified = HikkakeModified()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._hikkake_modified.update(bar)

        if self._hikkake_modified.is_ready:
            # The current value of self._hikkake_modified is represented by self._hikkake_modified.current.value
            self.plot("HikkakeModified", "hikkake_modified", self._hikkake_modified.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1HikkakeModified.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/HikkakeModified">reference</a>.</p>