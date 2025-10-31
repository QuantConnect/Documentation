<p>To create an automatic indicators for <code>MassIndex</code>, call the <code class='csharp'>MASS</code><code class='python'>mass</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>MASS</code><code class='python'>mass</code> method creates a <code>MassIndex</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class MassIndexAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private MassIndex _mass;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _mass = MASS(_symbol, 9, 25);
    &rcub;

    public override void OnData(Slice data)
    &lcub;

        if (_mass.IsReady)
        &lcub;
            // The current value of _mass is represented by itself (_mass)
            // or _mass.Current.Value
            Plot("MassIndex", "mass", _mass);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class MassIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._mass = self.mass(self._symbol, 9, 25)

    def on_data(self, slice: Slice) -> None:

        if self._mass.is_ready:
            # The current value of self._mass is represented by self._mass.current.value
            self.plot("MassIndex", "mass", self._mass.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.mass">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>MassIndex</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class MassIndexAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private MassIndex _massindex;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _massindex = new MassIndex(9, 25);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _massindex.Update(bar.EndTime, bar.Close);

        if (_massindex.IsReady)
        &lcub;
            // The current value of _massindex is represented by itself (_massindex)
            // or _massindex.Current.Value
            Plot("MassIndex", "massindex", _massindex);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class MassIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._massindex = MassIndex(9, 25)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._massindex.update(bar.end_time, bar.close)

        if self._massindex.is_ready:
            # The current value of self._massindex is represented by self._massindex.current.value
            self.plot("MassIndex", "massindex", self._massindex.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1MassIndex.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/MassIndex">reference</a>.</p>