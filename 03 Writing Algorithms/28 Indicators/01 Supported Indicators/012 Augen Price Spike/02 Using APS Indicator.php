<p>To create an automatic indicators for <code>AugenPriceSpike</code>, call the <code class='csharp'>APS</code><code class='python'>aps</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>APS</code><code class='python'>aps</code> method creates a <code>AugenPriceSpike</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class AugenPriceSpikeAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private AugenPriceSpike _aps;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _aps = APS(_symbol, 3);
    }

    public override void OnData(Slice data)
    {

        if (_aps.IsReady)
        {
            // The current value of _aps is represented by itself (_aps)
            // or _aps.Current.Value
            Plot("AugenPriceSpike", "aps", _aps);
        }
    }
}</pre>
<pre class="python">class AugenPriceSpikeAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._aps = self.aps(self._symbol, 3)

    def on_data(self, slice: Slice) -> None:

        if self._aps.is_ready:
            # The current value of self._aps is represented by self._aps.current.value
            self.plot("AugenPriceSpike", "aps", self._aps.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.aps">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>AugenPriceSpike</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class AugenPriceSpikeAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private AugenPriceSpike _augenpricespike;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _augenpricespike = new AugenPriceSpike(3);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _augenpricespike.Update(bar.EndTime, bar.Close);

        if (_augenpricespike.IsReady)
        {
            // The current value of _augenpricespike is represented by itself (_augenpricespike)
            // or _augenpricespike.Current.Value
            Plot("AugenPriceSpike", "augenpricespike", _augenpricespike);
        }
    }
}</pre>
<pre class="python">class AugenPriceSpikeAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._augenpricespike = AugenPriceSpike(3)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._augenpricespike.update(bar.end_time, bar.close)

        if self._augenpricespike.is_ready:
            # The current value of self._augenpricespike is represented by self._augenpricespike.current.value
            self.plot("AugenPriceSpike", "augenpricespike", self._augenpricespike.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1AugenPriceSpike.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/AugenPriceSpike">reference</a>.</p>