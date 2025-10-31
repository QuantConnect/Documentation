<p>To create an automatic indicators for <code>VolumeWeightedAveragePriceIndicator</code>, call the <code class='csharp'>VWAP</code><code class='python'>vwap</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>VWAP</code><code class='python'>vwap</code> method creates a <code>VolumeWeightedAveragePriceIndicator</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class VolumeWeightedAveragePriceIndicatorAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private VolumeWeightedAveragePriceIndicator _vwap;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _vwap = VWAP(_symbol, 20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_vwap.IsReady)
        &lcub;
            // The current value of _vwap is represented by itself (_vwap)
            // or _vwap.Current.Value
            Plot("VolumeWeightedAveragePriceIndicator", "vwap", _vwap);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class VolumeWeightedAveragePriceIndicatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._vwap = self.vwap(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:
        if self._vwap.is_ready:
            # The current value of self._vwap is represented by self._vwap.current.value
            self.plot("VolumeWeightedAveragePriceIndicator", "vwap", self._vwap.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.vwap">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>VolumeWeightedAveragePriceIndicator</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class VolumeWeightedAveragePriceIndicatorAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private VolumeWeightedAveragePriceIndicator _volumeweightedaveragepriceindicator;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _volumeweightedaveragepriceindicator = new VolumeWeightedAveragePriceIndicator(20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _volumeweightedaveragepriceindicator.Update(bar.EndTime, bar.Close);
        if (_volumeweightedaveragepriceindicator.IsReady)
        &lcub;
            // The current value of _volumeweightedaveragepriceindicator is represented by itself (_volumeweightedaveragepriceindicator)
            // or _volumeweightedaveragepriceindicator.Current.Value
            Plot("VolumeWeightedAveragePriceIndicator", "volumeweightedaveragepriceindicator", _volumeweightedaveragepriceindicator);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class VolumeWeightedAveragePriceIndicatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._volumeweightedaveragepriceindicator = VolumeWeightedAveragePriceIndicator(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._volumeweightedaveragepriceindicator.update(bar.end_time, bar.close)
        if self._volumeweightedaveragepriceindicator.is_ready:
            # The current value of self._volumeweightedaveragepriceindicator is represented by self._volumeweightedaveragepriceindicator.current.value
            self.plot("VolumeWeightedAveragePriceIndicator", "volumeweightedaveragepriceindicator", self._volumeweightedaveragepriceindicator.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1VolumeWeightedAveragePriceIndicator.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/VolumeWeightedAveragePriceIndicator">reference</a>.</p>