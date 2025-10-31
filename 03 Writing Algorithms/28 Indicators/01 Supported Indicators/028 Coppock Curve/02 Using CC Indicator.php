<p>To create an automatic indicators for <code>CoppockCurve</code>, call the <code class='csharp'>CC</code><code class='python'>cc</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>CC</code><code class='python'>cc</code> method creates a <code>CoppockCurve</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class CoppockCurveAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private CoppockCurve _cc;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _cc = CC(_symbol, 11, 14, 10);
    &rcub;

    public override void OnData(Slice data)
    &lcub;

        if (_cc.IsReady)
        &lcub;
            // The current value of _cc is represented by itself (_cc)
            // or _cc.Current.Value
            Plot("CoppockCurve", "cc", _cc);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class CoppockCurveAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._cc = self.cc(self._symbol, 11, 14, 10)

    def on_data(self, slice: Slice) -> None:

        if self._cc.is_ready:
            # The current value of self._cc is represented by self._cc.current.value
            self.plot("CoppockCurve", "cc", self._cc.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.cc">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>CoppockCurve</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class CoppockCurveAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private CoppockCurve _coppockcurve;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _coppockcurve = new CoppockCurve(11, 14, 10);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _coppockcurve.Update(bar.EndTime, bar.Close);

        if (_coppockcurve.IsReady)
        &lcub;
            // The current value of _coppockcurve is represented by itself (_coppockcurve)
            // or _coppockcurve.Current.Value
            Plot("CoppockCurve", "coppockcurve", _coppockcurve);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class CoppockCurveAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._coppockcurve = CoppockCurve(11, 14, 10)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._coppockcurve.update(bar.end_time, bar.close)

        if self._coppockcurve.is_ready:
            # The current value of self._coppockcurve is represented by self._coppockcurve.current.value
            self.plot("CoppockCurve", "coppockcurve", self._coppockcurve.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CoppockCurve.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CoppockCurve">reference</a>.</p>