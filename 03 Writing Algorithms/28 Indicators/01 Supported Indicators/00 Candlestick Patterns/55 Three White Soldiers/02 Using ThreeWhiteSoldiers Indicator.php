<p>To create an automatic indicators for <code>ThreeWhiteSoldiers</code>, call the <code class='csharp'>ThreeWhiteSoldiers</code><code class='python'>three_white_soldiers</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>ThreeWhiteSoldiers</code><code class='python'>three_white_soldiers</code> method creates a <code>ThreeWhiteSoldiers</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class ThreeWhiteSoldiersAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private ThreeWhiteSoldiers _threeWhiteSoldiers;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _threeWhiteSoldiers = CandlestickPatterns.ThreeWhiteSoldiers(_symbol);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_threeWhiteSoldiers.IsReady)
        &lcub;
            // The current value of _threeWhiteSoldiers is represented by itself (_threeWhiteSoldiers)
            // or _threeWhiteSoldiers.Current.Value
            Plot("ThreeWhiteSoldiers", "threeWhiteSoldiers", _threeWhiteSoldiers);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class ThreeWhiteSoldiersAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._three_white_soldiers = self.candlestick_patterns.three_white_soldiers(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._three_white_soldiers.is_ready:
            # The current value of self._three_white_soldiers is represented by self._three_white_soldiers.current.value
            self.plot("ThreeWhiteSoldiers", "three_white_soldiers", self._three_white_soldiers.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.three_white_soldiers">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>ThreeWhiteSoldiers</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class ThreeWhiteSoldiersAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private ThreeWhiteSoldiers _threeWhiteSoldiers;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _threeWhiteSoldiers = new ThreeWhiteSoldiers();
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _threeWhiteSoldiers.Update(bar);

        if (_threeWhiteSoldiers.IsReady)
        &lcub;
            // The current value of _threeWhiteSoldiers is represented by itself (_threeWhiteSoldiers)
            // or _threeWhiteSoldiers.Current.Value
            Plot("ThreeWhiteSoldiers", "threeWhiteSoldiers", _threeWhiteSoldiers);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class ThreeWhiteSoldiersAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._three_white_soldiers = ThreeWhiteSoldiers()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._three_white_soldiers.update(bar)

        if self._three_white_soldiers.is_ready:
            # The current value of self._three_white_soldiers is represented by self._three_white_soldiers.current.value
            self.plot("ThreeWhiteSoldiers", "three_white_soldiers", self._three_white_soldiers.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1ThreeWhiteSoldiers.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/ThreeWhiteSoldiers">reference</a>.</p>