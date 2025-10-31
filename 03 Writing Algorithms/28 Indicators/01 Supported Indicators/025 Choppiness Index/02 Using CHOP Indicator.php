<p>To create an automatic indicators for <code>ChoppinessIndex</code>, call the <code class='csharp'>CHOP</code><code class='python'>chop</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>CHOP</code><code class='python'>chop</code> method creates a <code>ChoppinessIndex</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class ChoppinessIndexAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private ChoppinessIndex _chop;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _chop = CHOP(_symbol, 14);
    &rcub;

    public override void OnData(Slice data)
    &lcub;

        if (_chop.IsReady)
        &lcub;
            // The current value of _chop is represented by itself (_chop)
            // or _chop.Current.Value
            Plot("ChoppinessIndex", "chop", _chop);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class ChoppinessIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._chop = self.chop(self._symbol, 14)

    def on_data(self, slice: Slice) -> None:

        if self._chop.is_ready:
            # The current value of self._chop is represented by self._chop.current.value
            self.plot("ChoppinessIndex", "chop", self._chop.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.chop">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>ChoppinessIndex</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class ChoppinessIndexAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private ChoppinessIndex _choppinessindex;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _choppinessindex = new ChoppinessIndex(14);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _choppinessindex.Update(bar.EndTime, bar.Close);

        if (_choppinessindex.IsReady)
        &lcub;
            // The current value of _choppinessindex is represented by itself (_choppinessindex)
            // or _choppinessindex.Current.Value
            Plot("ChoppinessIndex", "choppinessindex", _choppinessindex);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class ChoppinessIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._choppinessindex = ChoppinessIndex(14)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._choppinessindex.update(bar.end_time, bar.close)

        if self._choppinessindex.is_ready:
            # The current value of self._choppinessindex is represented by self._choppinessindex.current.value
            self.plot("ChoppinessIndex", "choppinessindex", self._choppinessindex.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1ChoppinessIndex.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/ChoppinessIndex">reference</a>.</p>