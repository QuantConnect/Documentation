<p>To create an automatic indicators for <code>Thrusting</code>, call the <code class='csharp'>Thrusting</code><code class='python'>thrusting</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>Thrusting</code><code class='python'>thrusting</code> method creates a <code>Thrusting</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class ThrustingAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private Thrusting _thrusting;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _thrusting = CandlestickPatterns.Thrusting(_symbol);
    }

    public override void OnData(Slice data)
    {
        if (_thrusting.IsReady)
        {
            // The current value of _thrusting is represented by itself (_thrusting)
            // or _thrusting.Current.Value
            Plot("Thrusting", "thrusting", _thrusting);
        }
    }
}</pre>
<pre class="python">class ThrustingAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._thrusting = self.candlestick_patterns.thrusting(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._thrusting.is_ready:
            # The current value of self._thrusting is represented by self._thrusting.current.value
            self.plot("Thrusting", "thrusting", self._thrusting.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.thrusting">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>Thrusting</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class ThrustingAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private Thrusting _thrusting;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _thrusting = new Thrusting();
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _thrusting.Update(bar);

        if (_thrusting.IsReady)
        {
            // The current value of _thrusting is represented by itself (_thrusting)
            // or _thrusting.Current.Value
            Plot("Thrusting", "thrusting", _thrusting);
        }
    }
}</pre>
<pre class="python">class ThrustingAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._thrusting = Thrusting()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._thrusting.update(bar)

        if self._thrusting.is_ready:
            # The current value of self._thrusting is represented by self._thrusting.current.value
            self.plot("Thrusting", "thrusting", self._thrusting.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1Thrusting.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/Thrusting">reference</a>.</p>