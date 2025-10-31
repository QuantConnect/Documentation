<p>To create an automatic indicators for <code>HomingPigeon</code>, call the <code class='csharp'>HomingPigeon</code><code class='python'>homing_pigeon</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>HomingPigeon</code><code class='python'>homing_pigeon</code> method creates a <code>HomingPigeon</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class HomingPigeonAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private HomingPigeon _homingPigeon;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _homingPigeon = CandlestickPatterns.HomingPigeon(_symbol);
    }

    public override void OnData(Slice data)
    {
        if (_homingPigeon.IsReady)
        {
            // The current value of _homingPigeon is represented by itself (_homingPigeon)
            // or _homingPigeon.Current.Value
            Plot("HomingPigeon", "homingPigeon", _homingPigeon);
        }
    }
}</pre>
<pre class="python">class HomingPigeonAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._homing_pigeon = self.candlestick_patterns.homing_pigeon(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._homing_pigeon.is_ready:
            # The current value of self._homing_pigeon is represented by self._homing_pigeon.current.value
            self.plot("HomingPigeon", "homing_pigeon", self._homing_pigeon.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.homing_pigeon">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>HomingPigeon</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class HomingPigeonAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private HomingPigeon _homingPigeon;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _homingPigeon = new HomingPigeon();
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _homingPigeon.Update(bar);

        if (_homingPigeon.IsReady)
        {
            // The current value of _homingPigeon is represented by itself (_homingPigeon)
            // or _homingPigeon.Current.Value
            Plot("HomingPigeon", "homingPigeon", _homingPigeon);
        }
    }
}</pre>
<pre class="python">class HomingPigeonAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._homing_pigeon = HomingPigeon()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._homing_pigeon.update(bar)

        if self._homing_pigeon.is_ready:
            # The current value of self._homing_pigeon is represented by self._homing_pigeon.current.value
            self.plot("HomingPigeon", "homing_pigeon", self._homing_pigeon.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1HomingPigeon.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/HomingPigeon">reference</a>.</p>