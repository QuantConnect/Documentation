<p>To create an automatic indicators for <code>AbandonedBaby</code>, call the <code class='csharp'>AbandonedBaby</code><code class='python'>abandoned_baby</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>AbandonedBaby</code><code class='python'>abandoned_baby</code> method creates a <code>AbandonedBaby</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class AbandonedBabyAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private AbandonedBaby _abandonedBaby;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _abandonedBaby = CandlestickPatterns.AbandonedBaby(_symbol);
    }

    public override void OnData(Slice data)
    {
        if (_abandonedBaby.IsReady)
        {
            // The current value of _abandonedBaby is represented by itself (_abandonedBaby)
            // or _abandonedBaby.Current.Value
            Plot("AbandonedBaby", "abandonedBaby", _abandonedBaby);
        }
    }
}</pre>
<pre class="python">class AbandonedBabyAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._abandoned_baby = self.candlestick_patterns.abandoned_baby(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._abandoned_baby.is_ready:
            # The current value of self._abandoned_baby is represented by self._abandoned_baby.current.value
            self.plot("AbandonedBaby", "abandoned_baby", self._abandoned_baby.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.abandoned_baby">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>AbandonedBaby</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class AbandonedBabyAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private AbandonedBaby _abandonedBaby;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _abandonedBaby = new AbandonedBaby();
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _abandonedBaby.Update(bar);

        if (_abandonedBaby.IsReady)
        {
            // The current value of _abandonedBaby is represented by itself (_abandonedBaby)
            // or _abandonedBaby.Current.Value
            Plot("AbandonedBaby", "abandonedBaby", _abandonedBaby);
        }
    }
}</pre>
<pre class="python">class AbandonedBabyAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._abandoned_baby = AbandonedBaby()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._abandoned_baby.update(bar)

        if self._abandoned_baby.is_ready:
            # The current value of self._abandoned_baby is represented by self._abandoned_baby.current.value
            self.plot("AbandonedBaby", "abandoned_baby", self._abandoned_baby.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1AbandonedBaby.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/AbandonedBaby">reference</a>.</p>