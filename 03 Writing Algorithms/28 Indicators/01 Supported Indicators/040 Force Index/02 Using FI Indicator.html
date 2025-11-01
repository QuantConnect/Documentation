<p>To create an automatic indicators for <code>ForceIndex</code>, call the <code class='csharp'>FI</code><code class='python'>fi</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>FI</code><code class='python'>fi</code> method creates a <code>ForceIndex</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class ForceIndexAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private ForceIndex _fi;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _fi = FI(_symbol, 13);
    }

    public override void OnData(Slice data)
    {

        if (_fi.IsReady)
        {
            // The current value of _fi is represented by itself (_fi)
            // or _fi.Current.Value
            Plot("ForceIndex", "fi", _fi);
        }
    }
}</pre>
<pre class="python">class ForceIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._fi = self.fi(self._symbol, 13)

    def on_data(self, slice: Slice) -> None:

        if self._fi.is_ready:
            # The current value of self._fi is represented by self._fi.current.value
            self.plot("ForceIndex", "fi", self._fi.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.fi">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>ForceIndex</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class ForceIndexAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private ForceIndex _forceindex;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _forceindex = new ForceIndex(13);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _forceindex.Update(bar.EndTime, bar.Close);

        if (_forceindex.IsReady)
        {
            // The current value of _forceindex is represented by itself (_forceindex)
            // or _forceindex.Current.Value
            Plot("ForceIndex", "forceindex", _forceindex);
        }
    }
}</pre>
<pre class="python">class ForceIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._forceindex = ForceIndex(13)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._forceindex.update(bar.end_time, bar.close)

        if self._forceindex.is_ready:
            # The current value of self._forceindex is represented by self._forceindex.current.value
            self.plot("ForceIndex", "forceindex", self._forceindex.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1ForceIndex.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/ForceIndex">reference</a>.</p>