<p>To create an automatic indicators for <code>McClellanSummationIndex</code>, call the <code class='csharp'>MSI</code><code class='python'>msi</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>MSI</code><code class='python'>msi</code> method creates a <code>McClellanSummationIndex</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class McClellanSummationIndexAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol,_reference;
    private McClellanSummationIndex _msi;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("QQQ", Resolution.Daily).Symbol;
        _reference = AddEquity("SPY", Resolution.Daily).Symbol;
        _msi = MSI([_symbol, _reference]);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_msi.IsReady)
        &lcub;
            // The current value of _msi is represented by itself (_msi)
            // or _msi.Current.Value
            Plot("McClellanSummationIndex", "msi", _msi);
            // Plot all properties of abands
            Plot("McClellanSummationIndex", "mcclellanoscillator", _msi.McClellanOscillator);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class McClellanSummationIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("QQQ", Resolution.DAILY).symbol
        self._reference = self.add_equity("SPY", Resolution.DAILY).symbol
        self._msi = self.msi([self._symbol, self._reference])

    def on_data(self, slice: Slice) -> None:
        if self._msi.is_ready:
            # The current value of self._msi is represented by self._msi.current.value
            self.plot("McClellanSummationIndex", "msi", self._msi.current.value)
            # Plot all attributes of self._msi
            self.plot("McClellanSummationIndex", "mc_clellan_oscillator", self._msi.mc_clellan_oscillator.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.msi">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>McClellanSummationIndex</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class McClellanSummationIndexAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private McClellanSummationIndex _mcclellansummationindex;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _mcclellansummationindex = new McClellanSummationIndex(19, 39);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _mcclellansummationindex.Update(bar.EndTime, bar.Close);
        if (_mcclellansummationindex.IsReady)
        &lcub;
            // The current value of _mcclellansummationindex is represented by itself (_mcclellansummationindex)
            // or _mcclellansummationindex.Current.Value
            Plot("McClellanSummationIndex", "mcclellansummationindex", _mcclellansummationindex);
            // Plot all properties of abands
            Plot("McClellanSummationIndex", "mcclellanoscillator", _mcclellansummationindex.McClellanOscillator);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class McClellanSummationIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._mcclellansummationindex = McClellanSummationIndex(19, 39)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._mcclellansummationindex.update(bar.end_time, bar.close)
        if self._mcclellansummationindex.is_ready:
            # The current value of self._mcclellansummationindex is represented by self._mcclellansummationindex.current.value
            self.plot("McClellanSummationIndex", "mcclellansummationindex", self._mcclellansummationindex.current.value)
            # Plot all attributes of self._mcclellansummationindex
            self.plot("McClellanSummationIndex", "mc_clellan_oscillator", self._mcclellansummationindex.mc_clellan_oscillator.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1McClellanSummationIndex.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/McClellanSummationIndex">reference</a>.</p>