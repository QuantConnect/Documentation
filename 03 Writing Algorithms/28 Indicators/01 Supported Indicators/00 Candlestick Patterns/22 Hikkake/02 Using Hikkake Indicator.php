<p>To create an automatic indicators for <code>Hikkake</code>, call the <code class='csharp'>Hikkake</code><code class='python'>hikkake</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>Hikkake</code><code class='python'>hikkake</code> method creates a <code>Hikkake</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class HikkakeAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private Hikkake _hikkake;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _hikkake = CandlestickPatterns.Hikkake(_symbol);
    }

    public override void OnData(Slice data)
    {
        if (_hikkake.IsReady)
        {
            // The current value of _hikkake is represented by itself (_hikkake)
            // or _hikkake.Current.Value
            Plot("Hikkake", "hikkake", _hikkake);
        }
    }
}</pre>
<pre class="python">class HikkakeAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._hikkake = self.candlestick_patterns.hikkake(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._hikkake.is_ready:
            # The current value of self._hikkake is represented by self._hikkake.current.value
            self.plot("Hikkake", "hikkake", self._hikkake.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.hikkake">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>Hikkake</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class HikkakeAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private Hikkake _hikkake;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _hikkake = new Hikkake();
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _hikkake.Update(bar);

        if (_hikkake.IsReady)
        {
            // The current value of _hikkake is represented by itself (_hikkake)
            // or _hikkake.Current.Value
            Plot("Hikkake", "hikkake", _hikkake);
        }
    }
}</pre>
<pre class="python">class HikkakeAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._hikkake = Hikkake()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._hikkake.update(bar)

        if self._hikkake.is_ready:
            # The current value of self._hikkake is represented by self._hikkake.current.value
            self.plot("Hikkake", "hikkake", self._hikkake.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1Hikkake.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/Hikkake">reference</a>.</p>