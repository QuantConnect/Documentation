<p>To create an automatic indicators for <code>StandardDeviation</code>, call the <code class='csharp'>STD</code><code class='python'>std</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>STD</code><code class='python'>std</code> method creates a <code>StandardDeviation</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class StandardDeviationAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private StandardDeviation _std;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _std = STD(_symbol, 22);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_std.IsReady)
        &lcub;
            // The current value of _std is represented by itself (_std)
            // or _std.Current.Value
            Plot("StandardDeviation", "std", _std);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class StandardDeviationAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._std = self.std(self._symbol, 22)

    def on_data(self, slice: Slice) -> None:
        if self._std.is_ready:
            # The current value of self._std is represented by self._std.current.value
            self.plot("StandardDeviation", "std", self._std.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.std">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>StandardDeviation</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class StandardDeviationAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private StandardDeviation _standarddeviation;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _standarddeviation = new StandardDeviation(22);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _standarddeviation.Update(bar.EndTime, bar.Close);
        if (_standarddeviation.IsReady)
        &lcub;
            // The current value of _standarddeviation is represented by itself (_standarddeviation)
            // or _standarddeviation.Current.Value
            Plot("StandardDeviation", "standarddeviation", _standarddeviation);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class StandardDeviationAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._standarddeviation = StandardDeviation(22)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._standarddeviation.update(bar.end_time, bar.close)
        if self._standarddeviation.is_ready:
            # The current value of self._standarddeviation is represented by self._standarddeviation.current.value
            self.plot("StandardDeviation", "standarddeviation", self._standarddeviation.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1StandardDeviation.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/StandardDeviation">reference</a>.</p>