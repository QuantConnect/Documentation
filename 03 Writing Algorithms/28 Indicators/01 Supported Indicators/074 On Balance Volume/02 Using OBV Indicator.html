<p>To create an automatic indicators for <code>OnBalanceVolume</code>, call the <code class='csharp'>OBV</code><code class='python'>obv</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>OBV</code><code class='python'>obv</code> method creates a <code>OnBalanceVolume</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class OnBalanceVolumeAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private OnBalanceVolume _obv;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _obv = OBV(_symbol);
    }

    public override void OnData(Slice data)
    {

        if (_obv.IsReady)
        {
            // The current value of _obv is represented by itself (_obv)
            // or _obv.Current.Value
            Plot("OnBalanceVolume", "obv", _obv);
        }
    }
}</pre>
<pre class="python">class OnBalanceVolumeAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._obv = self.obv(self._symbol)

    def on_data(self, slice: Slice) -> None:

        if self._obv.is_ready:
            # The current value of self._obv is represented by self._obv.current.value
            self.plot("OnBalanceVolume", "obv", self._obv.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.obv">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>OnBalanceVolume</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class OnBalanceVolumeAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private OnBalanceVolume _onbalancevolume;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _onbalancevolume = new OnBalanceVolume();
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _onbalancevolume.Update(bar.EndTime, bar.Close);

        if (_onbalancevolume.IsReady)
        {
            // The current value of _onbalancevolume is represented by itself (_onbalancevolume)
            // or _onbalancevolume.Current.Value
            Plot("OnBalanceVolume", "onbalancevolume", _onbalancevolume);
        }
    }
}</pre>
<pre class="python">class OnBalanceVolumeAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._onbalancevolume = OnBalanceVolume()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._onbalancevolume.update(bar.end_time, bar.close)

        if self._onbalancevolume.is_ready:
            # The current value of self._onbalancevolume is represented by self._onbalancevolume.current.value
            self.plot("OnBalanceVolume", "onbalancevolume", self._onbalancevolume.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1OnBalanceVolume.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/OnBalanceVolume">reference</a>.</p>