<p>To create an automatic indicators for <code>Variance</code>, call the <code class='csharp'>V</code><code class='python'>v</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>V</code><code class='python'>v</code> method creates a <code>Variance</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class VarianceAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private Variance _v;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _v = V(_symbol, 20);
    }

    public override void OnData(Slice data)
    {

        if (_v.IsReady)
        {
            // The current value of _v is represented by itself (_v)
            // or _v.Current.Value
            Plot("Variance", "v", _v);
        }
    }
}</pre>
<pre class="python">class VarianceAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._v = self.v(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:

        if self._v.is_ready:
            # The current value of self._v is represented by self._v.current.value
            self.plot("Variance", "v", self._v.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.v">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>Variance</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class VarianceAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private Variance _variance;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _variance = new Variance(20);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _variance.Update(bar.EndTime, bar.Close);

        if (_variance.IsReady)
        {
            // The current value of _variance is represented by itself (_variance)
            // or _variance.Current.Value
            Plot("Variance", "variance", _variance);
        }
    }
}</pre>
<pre class="python">class VarianceAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._variance = Variance(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._variance.update(bar.end_time, bar.close)

        if self._variance.is_ready:
            # The current value of self._variance is represented by self._variance.current.value
            self.plot("Variance", "variance", self._variance.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1Variance.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/Variance">reference</a>.</p>