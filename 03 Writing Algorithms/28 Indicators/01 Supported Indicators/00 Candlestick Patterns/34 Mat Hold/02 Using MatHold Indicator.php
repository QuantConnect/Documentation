<p>To create an automatic indicators for <code>MatHold</code>, call the <code class='csharp'>MatHold</code><code class='python'>mat_hold</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>MatHold</code><code class='python'>mat_hold</code> method creates a <code>MatHold</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class MatHoldAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private MatHold _matHold;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _matHold = CandlestickPatterns.MatHold(_symbol);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_matHold.IsReady)
        &lcub;
            // The current value of _matHold is represented by itself (_matHold)
            // or _matHold.Current.Value
            Plot("MatHold", "matHold", _matHold);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class MatHoldAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._mat_hold = self.candlestick_patterns.mat_hold(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._mat_hold.is_ready:
            # The current value of self._mat_hold is represented by self._mat_hold.current.value
            self.plot("MatHold", "mat_hold", self._mat_hold.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.mat_hold">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>MatHold</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class MatHoldAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private MatHold _matHold;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _matHold = new MatHold();
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _matHold.Update(bar);

        if (_matHold.IsReady)
        &lcub;
            // The current value of _matHold is represented by itself (_matHold)
            // or _matHold.Current.Value
            Plot("MatHold", "matHold", _matHold);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class MatHoldAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._mat_hold = MatHold()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._mat_hold.update(bar)

        if self._mat_hold.is_ready:
            # The current value of self._mat_hold is represented by self._mat_hold.current.value
            self.plot("MatHold", "mat_hold", self._mat_hold.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1MatHold.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/MatHold">reference</a>.</p>