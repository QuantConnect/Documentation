<p>To create an automatic indicators for <code>HaramiCross</code>, call the <code class='csharp'>HaramiCross</code><code class='python'>harami_cross</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>HaramiCross</code><code class='python'>harami_cross</code> method creates a <code>HaramiCross</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class HaramiCrossAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private HaramiCross _haramiCross;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _haramiCross = CandlestickPatterns.HaramiCross(_symbol);
    }

    public override void OnData(Slice data)
    {
        if (_haramiCross.IsReady)
        {
            // The current value of _haramiCross is represented by itself (_haramiCross)
            // or _haramiCross.Current.Value
            Plot("HaramiCross", "haramiCross", _haramiCross);
        }
    }
}</pre>
<pre class="python">class HaramiCrossAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._harami_cross = self.candlestick_patterns.harami_cross(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._harami_cross.is_ready:
            # The current value of self._harami_cross is represented by self._harami_cross.current.value
            self.plot("HaramiCross", "harami_cross", self._harami_cross.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.harami_cross">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>HaramiCross</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class HaramiCrossAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private HaramiCross _haramiCross;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _haramiCross = new HaramiCross();
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _haramiCross.Update(bar);

        if (_haramiCross.IsReady)
        {
            // The current value of _haramiCross is represented by itself (_haramiCross)
            // or _haramiCross.Current.Value
            Plot("HaramiCross", "haramiCross", _haramiCross);
        }
    }
}</pre>
<pre class="python">class HaramiCrossAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._harami_cross = HaramiCross()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._harami_cross.update(bar)

        if self._harami_cross.is_ready:
            # The current value of self._harami_cross is represented by self._harami_cross.current.value
            self.plot("HaramiCross", "harami_cross", self._harami_cross.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1HaramiCross.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/HaramiCross">reference</a>.</p>