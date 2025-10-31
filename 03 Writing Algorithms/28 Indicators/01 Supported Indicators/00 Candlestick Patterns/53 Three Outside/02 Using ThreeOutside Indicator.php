<p>To create an automatic indicators for <code>ThreeOutside</code>, call the <code class='csharp'>ThreeOutside</code><code class='python'>three_outside</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>ThreeOutside</code><code class='python'>three_outside</code> method creates a <code>ThreeOutside</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class ThreeOutsideAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private ThreeOutside _threeOutside;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _threeOutside = CandlestickPatterns.ThreeOutside(_symbol);
    }

    public override void OnData(Slice data)
    {
        if (_threeOutside.IsReady)
        {
            // The current value of _threeOutside is represented by itself (_threeOutside)
            // or _threeOutside.Current.Value
            Plot("ThreeOutside", "threeOutside", _threeOutside);
        }
    }
}</pre>
<pre class="python">class ThreeOutsideAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._three_outside = self.candlestick_patterns.three_outside(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._three_outside.is_ready:
            # The current value of self._three_outside is represented by self._three_outside.current.value
            self.plot("ThreeOutside", "three_outside", self._three_outside.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.three_outside">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>ThreeOutside</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class ThreeOutsideAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private ThreeOutside _threeOutside;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _threeOutside = new ThreeOutside();
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _threeOutside.Update(bar);

        if (_threeOutside.IsReady)
        {
            // The current value of _threeOutside is represented by itself (_threeOutside)
            // or _threeOutside.Current.Value
            Plot("ThreeOutside", "threeOutside", _threeOutside);
        }
    }
}</pre>
<pre class="python">class ThreeOutsideAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._three_outside = ThreeOutside()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._three_outside.update(bar)

        if self._three_outside.is_ready:
            # The current value of self._three_outside is represented by self._three_outside.current.value
            self.plot("ThreeOutside", "three_outside", self._three_outside.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1ThreeOutside.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/ThreeOutside">reference</a>.</p>