<p>To create an automatic indicators for <code>ParabolicStopAndReverse</code>, call the <code class='csharp'>PSAR</code><code class='python'>psar</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>PSAR</code><code class='python'>psar</code> method creates a <code>ParabolicStopAndReverse</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class ParabolicStopAndReverseAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private ParabolicStopAndReverse _psar;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _psar = PSAR(_symbol, 0.02m, 0.02m, 0.2m);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_psar.IsReady)
        &lcub;
            // The current value of _psar is represented by itself (_psar)
            // or _psar.Current.Value
            Plot("ParabolicStopAndReverse", "psar", _psar);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class ParabolicStopAndReverseAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._psar = self.psar(self._symbol, 0.02, 0.02, 0.2)

    def on_data(self, slice: Slice) -> None:
        if self._psar.is_ready:
            # The current value of self._psar is represented by self._psar.current.value
            self.plot("ParabolicStopAndReverse", "psar", self._psar.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.psar">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>ParabolicStopAndReverse</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class ParabolicStopAndReverseAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private ParabolicStopAndReverse _parabolicstopandreverse;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _parabolicstopandreverse = new ParabolicStopAndReverse(0.02m, 0.02m, 0.2m);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _parabolicstopandreverse.Update(bar.EndTime, bar.Close);
        if (_parabolicstopandreverse.IsReady)
        &lcub;
            // The current value of _parabolicstopandreverse is represented by itself (_parabolicstopandreverse)
            // or _parabolicstopandreverse.Current.Value
            Plot("ParabolicStopAndReverse", "parabolicstopandreverse", _parabolicstopandreverse);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class ParabolicStopAndReverseAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._parabolicstopandreverse = ParabolicStopAndReverse(0.02, 0.02, 0.2)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._parabolicstopandreverse.update(bar.end_time, bar.close)
        if self._parabolicstopandreverse.is_ready:
            # The current value of self._parabolicstopandreverse is represented by self._parabolicstopandreverse.current.value
            self.plot("ParabolicStopAndReverse", "parabolicstopandreverse", self._parabolicstopandreverse.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1ParabolicStopAndReverse.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/ParabolicStopAndReverse">reference</a>.</p>