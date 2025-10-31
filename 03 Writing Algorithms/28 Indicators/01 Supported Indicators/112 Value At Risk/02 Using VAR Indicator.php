<p>To create an automatic indicators for <code>ValueAtRisk</code>, call the <code class='csharp'>VAR</code><code class='python'>var</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>VAR</code><code class='python'>var</code> method creates a <code>ValueAtRisk</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class ValueAtRiskAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private ValueAtRisk _var;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _var = VAR(_symbol, 252, 0.95);
    &rcub;

    public override void OnData(Slice data)
    &lcub;

        if (_var.IsReady)
        &lcub;
            // The current value of _var is represented by itself (_var)
            // or _var.Current.Value
            Plot("ValueAtRisk", "var", _var);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class ValueAtRiskAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._var = self.var(self._symbol, 252, 0.95)

    def on_data(self, slice: Slice) -> None:

        if self._var.is_ready:
            # The current value of self._var is represented by self._var.current.value
            self.plot("ValueAtRisk", "var", self._var.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.var">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>ValueAtRisk</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class ValueAtRiskAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private ValueAtRisk _valueatrisk;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _valueatrisk = new ValueAtRisk(252, 0.95);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _valueatrisk.Update(bar.EndTime, bar.Close);

        if (_valueatrisk.IsReady)
        &lcub;
            // The current value of _valueatrisk is represented by itself (_valueatrisk)
            // or _valueatrisk.Current.Value
            Plot("ValueAtRisk", "valueatrisk", _valueatrisk);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class ValueAtRiskAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._valueatrisk = ValueAtRisk(252, 0.95)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._valueatrisk.update(bar.end_time, bar.close)

        if self._valueatrisk.is_ready:
            # The current value of self._valueatrisk is represented by self._valueatrisk.current.value
            self.plot("ValueAtRisk", "valueatrisk", self._valueatrisk.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1ValueAtRisk.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/ValueAtRisk">reference</a>.</p>