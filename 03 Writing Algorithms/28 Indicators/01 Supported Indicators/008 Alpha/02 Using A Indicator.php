<p>To create an automatic indicators for <code>Alpha</code>, call the <code class='csharp'>A</code><code class='python'>a</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>A</code><code class='python'>a</code> method creates a <code>Alpha</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class AlphaAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol,_reference;
    private Alpha _a;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("QQQ", Resolution.Daily).Symbol;
        _reference = AddEquity("SPY", Resolution.Daily).Symbol;
        _a = A(_symbol, _reference, 20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_a.IsReady)
        &lcub;
            // The current value of _a is represented by itself (_a)
            // or _a.Current.Value
            Plot("Alpha", "a", _a);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class AlphaAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("QQQ", Resolution.DAILY).symbol
        self._reference = self.add_equity("SPY", Resolution.DAILY).symbol
        self._a = self.a(self._symbol, self._reference, 20)

    def on_data(self, slice: Slice) -> None:
        if self._a.is_ready:
            # The current value of self._a is represented by self._a.current.value
            self.plot("Alpha", "a", self._a.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.a">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>Alpha</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class AlphaAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol,_reference;
    private Alpha _alpha;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("QQQ", Resolution.Daily).Symbol;
        _reference = AddEquity("SPY", Resolution.Daily).Symbol;
        _alpha = new Alpha("", _symbol, _reference, 20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _alpha.Update(bar.EndTime, bar.Close);
        if (data.Bars.TryGetValue(_reference, out var bar))
            _alpha.Update(bar.EndTime, bar.Close);
        if (_alpha.IsReady)
        &lcub;
            // The current value of _alpha is represented by itself (_alpha)
            // or _alpha.Current.Value
            Plot("Alpha", "alpha", _alpha);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class AlphaAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("QQQ", Resolution.DAILY).symbol
        self._reference = self.add_equity("SPY", Resolution.DAILY).symbol
        self._alpha = Alpha("", self._symbol, self._reference, 20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._alpha.update(bar.end_time, bar.close)
        bar = slice.bars.get(self._reference)
        if bar:
            self._alpha.update(bar.end_time, bar.close)
        if self._alpha.is_ready:
            # The current value of self._alpha is represented by self._alpha.current.value
            self.plot("Alpha", "alpha", self._alpha.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1Alpha.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/Alpha">reference</a>.</p>