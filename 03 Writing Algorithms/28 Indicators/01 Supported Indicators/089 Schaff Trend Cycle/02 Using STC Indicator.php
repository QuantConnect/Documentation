<p>To create an automatic indicators for <code>SchaffTrendCycle</code>, call the <code class='csharp'>STC</code><code class='python'>stc</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>STC</code><code class='python'>stc</code> method creates a <code>SchaffTrendCycle</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class SchaffTrendCycleAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private SchaffTrendCycle _stc;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _stc = STC(_symbol, 5, 10, 20, MovingAverageType.Exponential);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_stc.IsReady)
        &lcub;
            // The current value of _stc is represented by itself (_stc)
            // or _stc.Current.Value
            Plot("SchaffTrendCycle", "stc", _stc);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class SchaffTrendCycleAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._stc = self.stc(self._symbol, 5, 10, 20, MovingAverageType.EXPONENTIAL)

    def on_data(self, slice: Slice) -> None:
        if self._stc.is_ready:
            # The current value of self._stc is represented by self._stc.current.value
            self.plot("SchaffTrendCycle", "stc", self._stc.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.stc">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>SchaffTrendCycle</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class SchaffTrendCycleAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private SchaffTrendCycle _schafftrendcycle;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _schafftrendcycle = new SchaffTrendCycle(5, 10, 20, MovingAverageType.EXPONENTIAL);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _schafftrendcycle.Update(bar.EndTime, bar.Close);
        if (_schafftrendcycle.IsReady)
        &lcub;
            // The current value of _schafftrendcycle is represented by itself (_schafftrendcycle)
            // or _schafftrendcycle.Current.Value
            Plot("SchaffTrendCycle", "schafftrendcycle", _schafftrendcycle);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class SchaffTrendCycleAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._schafftrendcycle = SchaffTrendCycle(5, 10, 20, MovingAverageType.EXPONENTIAL)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._schafftrendcycle.update(bar.end_time, bar.close)
        if self._schafftrendcycle.is_ready:
            # The current value of self._schafftrendcycle is represented by self._schafftrendcycle.current.value
            self.plot("SchaffTrendCycle", "schafftrendcycle", self._schafftrendcycle.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1SchaffTrendCycle.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/SchaffTrendCycle">reference</a>.</p>