<p>To create an automatic indicators for <code>NormalizedAverageTrueRange</code>, call the <code class='csharp'>NATR</code><code class='python'>natr</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>NATR</code><code class='python'>natr</code> method creates a <code>NormalizedAverageTrueRange</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class NormalizedAverageTrueRangeAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private NormalizedAverageTrueRange _natr;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _natr = NATR(_symbol, 20);
    }

    public override void OnData(Slice data)
    {

        if (_natr.IsReady)
        {
            // The current value of _natr is represented by itself (_natr)
            // or _natr.Current.Value
            Plot("NormalizedAverageTrueRange", "natr", _natr);
        }
    }
}</pre>
<pre class="python">class NormalizedAverageTrueRangeAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._natr = self.natr(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:

        if self._natr.is_ready:
            # The current value of self._natr is represented by self._natr.current.value
            self.plot("NormalizedAverageTrueRange", "natr", self._natr.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.natr">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>NormalizedAverageTrueRange</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class NormalizedAverageTrueRangeAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private NormalizedAverageTrueRange _normalizedaveragetruerange;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _normalizedaveragetruerange = new NormalizedAverageTrueRange(20);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _normalizedaveragetruerange.Update(bar.EndTime, bar.Close);

        if (_normalizedaveragetruerange.IsReady)
        {
            // The current value of _normalizedaveragetruerange is represented by itself (_normalizedaveragetruerange)
            // or _normalizedaveragetruerange.Current.Value
            Plot("NormalizedAverageTrueRange", "normalizedaveragetruerange", _normalizedaveragetruerange);
        }
    }
}</pre>
<pre class="python">class NormalizedAverageTrueRangeAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._normalizedaveragetruerange = NormalizedAverageTrueRange(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._normalizedaveragetruerange.update(bar.end_time, bar.close)

        if self._normalizedaveragetruerange.is_ready:
            # The current value of self._normalizedaveragetruerange is represented by self._normalizedaveragetruerange.current.value
            self.plot("NormalizedAverageTrueRange", "normalizedaveragetruerange", self._normalizedaveragetruerange.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1NormalizedAverageTrueRange.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/NormalizedAverageTrueRange">reference</a>.</p>