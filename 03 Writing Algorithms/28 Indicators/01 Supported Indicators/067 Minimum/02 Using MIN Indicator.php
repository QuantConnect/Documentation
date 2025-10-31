<p>To create an automatic indicators for <code>Minimum</code>, call the <code class='csharp'>MIN</code><code class='python'>min</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>MIN</code><code class='python'>min</code> method creates a <code>Minimum</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class MinimumAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private Minimum _min;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _min = MIN(_symbol, 20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;

        if (_min.IsReady)
        &lcub;
            // The current value of _min is represented by itself (_min)
            // or _min.Current.Value
            Plot("Minimum", "min", _min);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class MinimumAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._min = self.min(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:

        if self._min.is_ready:
            # The current value of self._min is represented by self._min.current.value
            self.plot("Minimum", "min", self._min.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.min">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>Minimum</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class MinimumAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private Minimum _minimum;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _minimum = new Minimum(20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _minimum.Update(bar.EndTime, bar.Close);

        if (_minimum.IsReady)
        &lcub;
            // The current value of _minimum is represented by itself (_minimum)
            // or _minimum.Current.Value
            Plot("Minimum", "minimum", _minimum);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class MinimumAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._minimum = Minimum(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._minimum.update(bar.end_time, bar.close)

        if self._minimum.is_ready:
            # The current value of self._minimum is represented by self._minimum.current.value
            self.plot("Minimum", "minimum", self._minimum.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1Minimum.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/Minimum">reference</a>.</p>