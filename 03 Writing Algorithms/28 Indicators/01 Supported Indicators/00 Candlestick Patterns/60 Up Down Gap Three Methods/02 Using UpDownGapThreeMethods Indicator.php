<p>To create an automatic indicators for <code>UpDownGapThreeMethods</code>, call the <code class='csharp'>UpDownGapThreeMethods</code><code class='python'>up_down_gap_three_methods</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>UpDownGapThreeMethods</code><code class='python'>up_down_gap_three_methods</code> method creates a <code>UpDownGapThreeMethods</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class UpDownGapThreeMethodsAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private UpDownGapThreeMethods _upDownGapThreeMethods;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _upDownGapThreeMethods = CandlestickPatterns.UpDownGapThreeMethods(_symbol);
    }

    public override void OnData(Slice data)
    {
        if (_upDownGapThreeMethods.IsReady)
        {
            // The current value of _upDownGapThreeMethods is represented by itself (_upDownGapThreeMethods)
            // or _upDownGapThreeMethods.Current.Value
            Plot("UpDownGapThreeMethods", "upDownGapThreeMethods", _upDownGapThreeMethods);
        }
    }
}</pre>
<pre class="python">class UpDownGapThreeMethodsAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._up_down_gap_three_methods = self.candlestick_patterns.up_down_gap_three_methods(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._up_down_gap_three_methods.is_ready:
            # The current value of self._up_down_gap_three_methods is represented by self._up_down_gap_three_methods.current.value
            self.plot("UpDownGapThreeMethods", "up_down_gap_three_methods", self._up_down_gap_three_methods.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.up_down_gap_three_methods">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>UpDownGapThreeMethods</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class UpDownGapThreeMethodsAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private UpDownGapThreeMethods _upDownGapThreeMethods;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _upDownGapThreeMethods = new UpDownGapThreeMethods();
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _upDownGapThreeMethods.Update(bar);

        if (_upDownGapThreeMethods.IsReady)
        {
            // The current value of _upDownGapThreeMethods is represented by itself (_upDownGapThreeMethods)
            // or _upDownGapThreeMethods.Current.Value
            Plot("UpDownGapThreeMethods", "upDownGapThreeMethods", _upDownGapThreeMethods);
        }
    }
}</pre>
<pre class="python">class UpDownGapThreeMethodsAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._up_down_gap_three_methods = UpDownGapThreeMethods()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._up_down_gap_three_methods.update(bar)

        if self._up_down_gap_three_methods.is_ready:
            # The current value of self._up_down_gap_three_methods is represented by self._up_down_gap_three_methods.current.value
            self.plot("UpDownGapThreeMethods", "up_down_gap_three_methods", self._up_down_gap_three_methods.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1UpDownGapThreeMethods.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/UpDownGapThreeMethods">reference</a>.</p>