<p>To create an automatic indicators for <code>AverageRange</code>, call the <code class='csharp'>AR</code><code class='python'>ar</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>AR</code><code class='python'>ar</code> method creates a <code>AverageRange</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class AverageRangeAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private AverageRange _ar;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _ar = AR(_symbol, 20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;

        if (_ar.IsReady)
        &lcub;
            // The current value of _ar is represented by itself (_ar)
            // or _ar.Current.Value
            Plot("AverageRange", "ar", _ar);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class AverageRangeAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._ar = self.ar(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:

        if self._ar.is_ready:
            # The current value of self._ar is represented by self._ar.current.value
            self.plot("AverageRange", "ar", self._ar.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.ar">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>AverageRange</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class AverageRangeAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private AverageRange _averagerange;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _averagerange = new AverageRange(20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _averagerange.Update(bar.EndTime, bar.Close);

        if (_averagerange.IsReady)
        &lcub;
            // The current value of _averagerange is represented by itself (_averagerange)
            // or _averagerange.Current.Value
            Plot("AverageRange", "averagerange", _averagerange);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class AverageRangeAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._averagerange = AverageRange(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._averagerange.update(bar.end_time, bar.close)

        if self._averagerange.is_ready:
            # The current value of self._averagerange is represented by self._averagerange.current.value
            self.plot("AverageRange", "averagerange", self._averagerange.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1AverageRange.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/AverageRange">reference</a>.</p>