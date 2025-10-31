<p>To create an automatic indicators for <code>LadderBottom</code>, call the <code class='csharp'>LadderBottom</code><code class='python'>ladder_bottom</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>LadderBottom</code><code class='python'>ladder_bottom</code> method creates a <code>LadderBottom</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class LadderBottomAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private LadderBottom _ladderBottom;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _ladderBottom = CandlestickPatterns.LadderBottom(_symbol);
    }

    public override void OnData(Slice data)
    {
        if (_ladderBottom.IsReady)
        {
            // The current value of _ladderBottom is represented by itself (_ladderBottom)
            // or _ladderBottom.Current.Value
            Plot("LadderBottom", "ladderBottom", _ladderBottom);
        }
    }
}</pre>
<pre class="python">class LadderBottomAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._ladder_bottom = self.candlestick_patterns.ladder_bottom(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._ladder_bottom.is_ready:
            # The current value of self._ladder_bottom is represented by self._ladder_bottom.current.value
            self.plot("LadderBottom", "ladder_bottom", self._ladder_bottom.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.ladder_bottom">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>LadderBottom</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class LadderBottomAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private LadderBottom _ladderBottom;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _ladderBottom = new LadderBottom();
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _ladderBottom.Update(bar);

        if (_ladderBottom.IsReady)
        {
            // The current value of _ladderBottom is represented by itself (_ladderBottom)
            // or _ladderBottom.Current.Value
            Plot("LadderBottom", "ladderBottom", _ladderBottom);
        }
    }
}</pre>
<pre class="python">class LadderBottomAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._ladder_bottom = LadderBottom()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._ladder_bottom.update(bar)

        if self._ladder_bottom.is_ready:
            # The current value of self._ladder_bottom is represented by self._ladder_bottom.current.value
            self.plot("LadderBottom", "ladder_bottom", self._ladder_bottom.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1LadderBottom.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/LadderBottom">reference</a>.</p>