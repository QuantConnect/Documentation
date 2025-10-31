<p>To create an automatic indicators for <code>WilderAccumulativeSwingIndex</code>, call the <code class='csharp'>ASI</code><code class='python'>asi</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>ASI</code><code class='python'>asi</code> method creates a <code>WilderAccumulativeSwingIndex</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class WilderAccumulativeSwingIndexAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private WilderAccumulativeSwingIndex _asi;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _asi = ASI(_symbol, 20);
    }

    public override void OnData(Slice data)
    {

        if (_asi.IsReady)
        {
            // The current value of _asi is represented by itself (_asi)
            // or _asi.Current.Value
            Plot("WilderAccumulativeSwingIndex", "asi", _asi);
        }
    }
}</pre>
<pre class="python">class WilderAccumulativeSwingIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._asi = self.asi(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:

        if self._asi.is_ready:
            # The current value of self._asi is represented by self._asi.current.value
            self.plot("WilderAccumulativeSwingIndex", "asi", self._asi.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.asi">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>WilderAccumulativeSwingIndex</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class WilderAccumulativeSwingIndexAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private WilderAccumulativeSwingIndex _wilderaccumulativeswingindex;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _wilderaccumulativeswingindex = new WilderAccumulativeSwingIndex(20);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _wilderaccumulativeswingindex.Update(bar.EndTime, bar.Close);

        if (_wilderaccumulativeswingindex.IsReady)
        {
            // The current value of _wilderaccumulativeswingindex is represented by itself (_wilderaccumulativeswingindex)
            // or _wilderaccumulativeswingindex.Current.Value
            Plot("WilderAccumulativeSwingIndex", "wilderaccumulativeswingindex", _wilderaccumulativeswingindex);
        }
    }
}</pre>
<pre class="python">class WilderAccumulativeSwingIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._wilderaccumulativeswingindex = WilderAccumulativeSwingIndex(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._wilderaccumulativeswingindex.update(bar.end_time, bar.close)

        if self._wilderaccumulativeswingindex.is_ready:
            # The current value of self._wilderaccumulativeswingindex is represented by self._wilderaccumulativeswingindex.current.value
            self.plot("WilderAccumulativeSwingIndex", "wilderaccumulativeswingindex", self._wilderaccumulativeswingindex.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1WilderAccumulativeSwingIndex.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/WilderAccumulativeSwingIndex">reference</a>.</p>