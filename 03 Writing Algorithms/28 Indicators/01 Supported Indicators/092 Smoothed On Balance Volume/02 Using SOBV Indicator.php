<p>To create an automatic indicators for <code>SmoothedOnBalanceVolume</code>, call the <code class='csharp'>SOBV</code><code class='python'>sobv</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>SOBV</code><code class='python'>sobv</code> method creates a <code>SmoothedOnBalanceVolume</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class SmoothedOnBalanceVolumeAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private SmoothedOnBalanceVolume _sobv;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _sobv = SOBV(_symbol, 20);
    }

    public override void OnData(Slice data)
    {

        if (_sobv.IsReady)
        {
            // The current value of _sobv is represented by itself (_sobv)
            // or _sobv.Current.Value
            Plot("SmoothedOnBalanceVolume", "sobv", _sobv);
            // Plot all properties of abands
            Plot("SmoothedOnBalanceVolume", "onbalancevolume", _sobv.OnBalanceVolume);
        }
    }
}</pre>
<pre class="python">class SmoothedOnBalanceVolumeAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._sobv = self.sobv(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:

        if self._sobv.is_ready:
            # The current value of self._sobv is represented by self._sobv.current.value
            self.plot("SmoothedOnBalanceVolume", "sobv", self._sobv.current.value)
            # Plot all attributes of self._sobv
            self.plot("SmoothedOnBalanceVolume", "on_balance_volume", self._sobv.on_balance_volume.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.sobv">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>SmoothedOnBalanceVolume</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class SmoothedOnBalanceVolumeAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private SmoothedOnBalanceVolume _smoothedonbalancevolume;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _smoothedonbalancevolume = new SmoothedOnBalanceVolume(20);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _smoothedonbalancevolume.Update(bar.EndTime, bar.Close);

        if (_smoothedonbalancevolume.IsReady)
        {
            // The current value of _smoothedonbalancevolume is represented by itself (_smoothedonbalancevolume)
            // or _smoothedonbalancevolume.Current.Value
            Plot("SmoothedOnBalanceVolume", "smoothedonbalancevolume", _smoothedonbalancevolume);
            // Plot all properties of abands
            Plot("SmoothedOnBalanceVolume", "onbalancevolume", _smoothedonbalancevolume.OnBalanceVolume);
        }
    }
}</pre>
<pre class="python">class SmoothedOnBalanceVolumeAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._smoothedonbalancevolume = SmoothedOnBalanceVolume(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._smoothedonbalancevolume.update(bar.end_time, bar.close)

        if self._smoothedonbalancevolume.is_ready:
            # The current value of self._smoothedonbalancevolume is represented by self._smoothedonbalancevolume.current.value
            self.plot("SmoothedOnBalanceVolume", "smoothedonbalancevolume", self._smoothedonbalancevolume.current.value)
            # Plot all attributes of self._smoothedonbalancevolume
            self.plot("SmoothedOnBalanceVolume", "on_balance_volume", self._smoothedonbalancevolume.on_balance_volume.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1SmoothedOnBalanceVolume.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/SmoothedOnBalanceVolume">reference</a>.</p>