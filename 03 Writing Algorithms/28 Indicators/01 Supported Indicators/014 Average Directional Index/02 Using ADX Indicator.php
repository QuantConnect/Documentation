<p>To create an automatic indicators for <code>AverageDirectionalIndex</code>, call the <code class='csharp'>ADX</code><code class='python'>adx</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>ADX</code><code class='python'>adx</code> method creates a <code>AverageDirectionalIndex</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class AverageDirectionalIndexAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private AverageDirectionalIndex _adx;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _adx = ADX(_symbol, 20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_adx.IsReady)
        &lcub;
            // The current value of _adx is represented by itself (_adx)
            // or _adx.Current.Value
            Plot("AverageDirectionalIndex", "adx", _adx);
            // Plot all properties of abands
            Plot("AverageDirectionalIndex", "positivedirectionalindex", _adx.PositiveDirectionalIndex);
            Plot("AverageDirectionalIndex", "negativedirectionalindex", _adx.NegativeDirectionalIndex);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class AverageDirectionalIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._adx = self.adx(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:
        if self._adx.is_ready:
            # The current value of self._adx is represented by self._adx.current.value
            self.plot("AverageDirectionalIndex", "adx", self._adx.current.value)
            # Plot all attributes of self._adx
            self.plot("AverageDirectionalIndex", "positive_directional_index", self._adx.positive_directional_index.current.value)
            self.plot("AverageDirectionalIndex", "negative_directional_index", self._adx.negative_directional_index.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.adx">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>AverageDirectionalIndex</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class AverageDirectionalIndexAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private AverageDirectionalIndex _averagedirectionalindex;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _averagedirectionalindex = new AverageDirectionalIndex(20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _averagedirectionalindex.Update(bar.EndTime, bar.Close);
        if (_averagedirectionalindex.IsReady)
        &lcub;
            // The current value of _averagedirectionalindex is represented by itself (_averagedirectionalindex)
            // or _averagedirectionalindex.Current.Value
            Plot("AverageDirectionalIndex", "averagedirectionalindex", _averagedirectionalindex);
            // Plot all properties of abands
            Plot("AverageDirectionalIndex", "positivedirectionalindex", _averagedirectionalindex.PositiveDirectionalIndex);
            Plot("AverageDirectionalIndex", "negativedirectionalindex", _averagedirectionalindex.NegativeDirectionalIndex);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class AverageDirectionalIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._averagedirectionalindex = AverageDirectionalIndex(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._averagedirectionalindex.update(bar.end_time, bar.close)
        if self._averagedirectionalindex.is_ready:
            # The current value of self._averagedirectionalindex is represented by self._averagedirectionalindex.current.value
            self.plot("AverageDirectionalIndex", "averagedirectionalindex", self._averagedirectionalindex.current.value)
            # Plot all attributes of self._averagedirectionalindex
            self.plot("AverageDirectionalIndex", "positive_directional_index", self._averagedirectionalindex.positive_directional_index.current.value)
            self.plot("AverageDirectionalIndex", "negative_directional_index", self._averagedirectionalindex.negative_directional_index.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1AverageDirectionalIndex.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/AverageDirectionalIndex">reference</a>.</p>