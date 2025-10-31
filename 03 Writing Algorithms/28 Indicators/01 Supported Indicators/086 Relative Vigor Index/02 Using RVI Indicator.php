<p>To create an automatic indicators for <code>RelativeVigorIndex</code>, call the <code class='csharp'>RVI</code><code class='python'>rvi</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>RVI</code><code class='python'>rvi</code> method creates a <code>RelativeVigorIndex</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class RelativeVigorIndexAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private RelativeVigorIndex _rvi;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _rvi = RVI(_symbol, 20, MovingAverageType.Simple);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_rvi.IsReady)
        &lcub;
            // The current value of _rvi is represented by itself (_rvi)
            // or _rvi.Current.Value
            Plot("RelativeVigorIndex", "rvi", _rvi);
            // Plot all properties of abands
            Plot("RelativeVigorIndex", "signal", _rvi.Signal);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class RelativeVigorIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._rvi = self.rvi(self._symbol, 20, MovingAverageType.SIMPLE)

    def on_data(self, slice: Slice) -> None:
        if self._rvi.is_ready:
            # The current value of self._rvi is represented by self._rvi.current.value
            self.plot("RelativeVigorIndex", "rvi", self._rvi.current.value)
            # Plot all attributes of self._rvi
            self.plot("RelativeVigorIndex", "signal", self._rvi.signal.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.rvi">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>RelativeVigorIndex</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class RelativeVigorIndexAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private RelativeVigorIndex _relativevigorindex;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _relativevigorindex = new RelativeVigorIndex(20, MovingAverageType.Simple);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _relativevigorindex.Update(bar.EndTime, bar.Close);
        if (_relativevigorindex.IsReady)
        &lcub;
            // The current value of _relativevigorindex is represented by itself (_relativevigorindex)
            // or _relativevigorindex.Current.Value
            Plot("RelativeVigorIndex", "relativevigorindex", _relativevigorindex);
            // Plot all properties of abands
            Plot("RelativeVigorIndex", "signal", _relativevigorindex.Signal);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class RelativeVigorIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._relativevigorindex = RelativeVigorIndex(20, MovingAverageType.SIMPLE)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._relativevigorindex.update(bar.end_time, bar.close)
        if self._relativevigorindex.is_ready:
            # The current value of self._relativevigorindex is represented by self._relativevigorindex.current.value
            self.plot("RelativeVigorIndex", "relativevigorindex", self._relativevigorindex.current.value)
            # Plot all attributes of self._relativevigorindex
            self.plot("RelativeVigorIndex", "signal", self._relativevigorindex.signal.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1RelativeVigorIndex.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/RelativeVigorIndex">reference</a>.</p>