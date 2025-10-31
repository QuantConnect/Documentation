<p>To create an automatic indicators for <code>VolumeWeightedMovingAverage</code>, call the <code class='csharp'>VWMA</code><code class='python'>vwma</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>VWMA</code><code class='python'>vwma</code> method creates a <code>VolumeWeightedMovingAverage</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class VolumeWeightedMovingAverageAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private VolumeWeightedMovingAverage _vwma;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _vwma = VWMA(_symbol, 20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_vwma.IsReady)
        &lcub;
            // The current value of _vwma is represented by itself (_vwma)
            // or _vwma.Current.Value
            Plot("VolumeWeightedMovingAverage", "vwma", _vwma);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class VolumeWeightedMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._vwma = self.vwma(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:
        if self._vwma.is_ready:
            # The current value of self._vwma is represented by self._vwma.current.value
            self.plot("VolumeWeightedMovingAverage", "vwma", self._vwma.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.vwma">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>VolumeWeightedMovingAverage</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class VolumeWeightedMovingAverageAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private VolumeWeightedMovingAverage _volumeweightedmovingaverage;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _volumeweightedmovingaverage = new VolumeWeightedMovingAverage(20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _volumeweightedmovingaverage.Update(bar.EndTime, bar.Close);
        if (_volumeweightedmovingaverage.IsReady)
        &lcub;
            // The current value of _volumeweightedmovingaverage is represented by itself (_volumeweightedmovingaverage)
            // or _volumeweightedmovingaverage.Current.Value
            Plot("VolumeWeightedMovingAverage", "volumeweightedmovingaverage", _volumeweightedmovingaverage);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class VolumeWeightedMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._volumeweightedmovingaverage = VolumeWeightedMovingAverage(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._volumeweightedmovingaverage.update(bar.end_time, bar.close)
        if self._volumeweightedmovingaverage.is_ready:
            # The current value of self._volumeweightedmovingaverage is represented by self._volumeweightedmovingaverage.current.value
            self.plot("VolumeWeightedMovingAverage", "volumeweightedmovingaverage", self._volumeweightedmovingaverage.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1VolumeWeightedMovingAverage.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/VolumeWeightedMovingAverage">reference</a>.</p>