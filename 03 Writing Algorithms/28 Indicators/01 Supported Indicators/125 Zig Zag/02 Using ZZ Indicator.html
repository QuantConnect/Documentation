<p>To create an automatic indicators for <code>ZigZag</code>, call the <code class='csharp'>ZZ</code><code class='python'>zz</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>ZZ</code><code class='python'>zz</code> method creates a <code>ZigZag</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class ZigZagAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private ZigZag _zz;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _zz = ZZ(_symbol, 0.05m, 1);
    }

    public override void OnData(Slice data)
    {

        if (_zz.IsReady)
        {
            // The current value of _zz is represented by itself (_zz)
            // or _zz.Current.Value
            Plot("ZigZag", "zz", _zz);
            // Plot all properties of abands
            Plot("ZigZag", "highpivot", _zz.HighPivot);
            Plot("ZigZag", "lowpivot", _zz.LowPivot);
        }
    }
}</pre>
<pre class="python">class ZigZagAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._zz = self.zz(self._symbol, 0.05, 1)

    def on_data(self, slice: Slice) -> None:

        if self._zz.is_ready:
            # The current value of self._zz is represented by self._zz.current.value
            self.plot("ZigZag", "zz", self._zz.current.value)
            # Plot all attributes of self._zz
            self.plot("ZigZag", "high_pivot", self._zz.high_pivot.current.value)
            self.plot("ZigZag", "low_pivot", self._zz.low_pivot.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.zz">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>ZigZag</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class ZigZagAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private ZigZag _zigzag;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _zigzag = new ZigZag(0.05m, 1);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _zigzag.Update(bar.EndTime, bar.Close);

        if (_zigzag.IsReady)
        {
            // The current value of _zigzag is represented by itself (_zigzag)
            // or _zigzag.Current.Value
            Plot("ZigZag", "zigzag", _zigzag);
            // Plot all properties of abands
            Plot("ZigZag", "highpivot", _zigzag.HighPivot);
            Plot("ZigZag", "lowpivot", _zigzag.LowPivot);
        }
    }
}</pre>
<pre class="python">class ZigZagAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._zigzag = ZigZag(0.05, 1)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._zigzag.update(bar.end_time, bar.close)

        if self._zigzag.is_ready:
            # The current value of self._zigzag is represented by self._zigzag.current.value
            self.plot("ZigZag", "zigzag", self._zigzag.current.value)
            # Plot all attributes of self._zigzag
            self.plot("ZigZag", "high_pivot", self._zigzag.high_pivot.current.value)
            self.plot("ZigZag", "low_pivot", self._zigzag.low_pivot.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1ZigZag.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/ZigZag">reference</a>.</p>