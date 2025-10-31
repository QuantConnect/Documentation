<p>To create an automatic indicators for <code>VolumeProfile</code>, call the <code class='csharp'>VP</code><code class='python'>vp</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>VP</code><code class='python'>vp</code> method creates a <code>VolumeProfile</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class VolumeProfileAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private VolumeProfile _vp;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _vp = VP(_symbol, 3, 0.70m, 0.05m);
    }

    public override void OnData(Slice data)
    {

        if (_vp.IsReady)
        {
            // The current value of _vp is represented by itself (_vp)
            // or _vp.Current.Value
            Plot("VolumeProfile", "vp", _vp);
        }
    }
}</pre>
<pre class="python">class VolumeProfileAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._vp = self.vp(self._symbol, 3, 0.70, 0.05)

    def on_data(self, slice: Slice) -> None:

        if self._vp.is_ready:
            # The current value of self._vp is represented by self._vp.current.value
            self.plot("VolumeProfile", "vp", self._vp.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.vp">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>VolumeProfile</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class VolumeProfileAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private VolumeProfile _volumeprofile;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _volumeprofile = new VolumeProfile("VP", 3, 0.70m, 0.05m);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _volumeprofile.Update(bar.EndTime, bar.Close);

        if (_volumeprofile.IsReady)
        {
            // The current value of _volumeprofile is represented by itself (_volumeprofile)
            // or _volumeprofile.Current.Value
            Plot("VolumeProfile", "volumeprofile", _volumeprofile);
        }
    }
}</pre>
<pre class="python">class VolumeProfileAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._volumeprofile = VolumeProfile("VP", 3, 0.70, 0.05)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._volumeprofile.update(bar.end_time, bar.close)

        if self._volumeprofile.is_ready:
            # The current value of self._volumeprofile is represented by self._volumeprofile.current.value
            self.plot("VolumeProfile", "volumeprofile", self._volumeprofile.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1VolumeProfile.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/VolumeProfile">reference</a>.</p>