<p>To create an automatic indicators for <code>TrueRange</code>, call the <code class='csharp'>TR</code><code class='python'>tr</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>TR</code><code class='python'>tr</code> method creates a <code>TrueRange</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class TrueRangeAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private TrueRange _tr;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _tr = TR(_symbol);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_tr.IsReady)
        &lcub;
            // The current value of _tr is represented by itself (_tr)
            // or _tr.Current.Value
            Plot("TrueRange", "tr", _tr);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class TrueRangeAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._tr = self.tr(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._tr.is_ready:
            # The current value of self._tr is represented by self._tr.current.value
            self.plot("TrueRange", "tr", self._tr.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.tr">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>TrueRange</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class TrueRangeAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private TrueRange _truerange;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _truerange = new TrueRange();
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _truerange.Update(bar.EndTime, bar.Close);
        if (_truerange.IsReady)
        &lcub;
            // The current value of _truerange is represented by itself (_truerange)
            // or _truerange.Current.Value
            Plot("TrueRange", "truerange", _truerange);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class TrueRangeAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._truerange = TrueRange()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._truerange.update(bar.end_time, bar.close)
        if self._truerange.is_ready:
            # The current value of self._truerange is represented by self._truerange.current.value
            self.plot("TrueRange", "truerange", self._truerange.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1TrueRange.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/TrueRange">reference</a>.</p>