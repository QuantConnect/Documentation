<p>To create an automatic indicators for <code>WilderSwingIndex</code>, call the <code class='csharp'>SI</code><code class='python'>si</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>SI</code><code class='python'>si</code> method creates a <code>WilderSwingIndex</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class WilderSwingIndexAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private WilderSwingIndex _si;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _si = SI(_symbol, 20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_si.IsReady)
        &lcub;
            // The current value of _si is represented by itself (_si)
            // or _si.Current.Value
            Plot("WilderSwingIndex", "si", _si);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class WilderSwingIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._si = self.si(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:
        if self._si.is_ready:
            # The current value of self._si is represented by self._si.current.value
            self.plot("WilderSwingIndex", "si", self._si.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.si">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>WilderSwingIndex</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class WilderSwingIndexAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private WilderSwingIndex _wilderswingindex;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _wilderswingindex = new WilderSwingIndex(20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _wilderswingindex.Update(bar.EndTime, bar.Close);
        if (_wilderswingindex.IsReady)
        &lcub;
            // The current value of _wilderswingindex is represented by itself (_wilderswingindex)
            // or _wilderswingindex.Current.Value
            Plot("WilderSwingIndex", "wilderswingindex", _wilderswingindex);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class WilderSwingIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._wilderswingindex = WilderSwingIndex(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._wilderswingindex.update(bar.end_time, bar.close)
        if self._wilderswingindex.is_ready:
            # The current value of self._wilderswingindex is represented by self._wilderswingindex.current.value
            self.plot("WilderSwingIndex", "wilderswingindex", self._wilderswingindex.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1WilderSwingIndex.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/WilderSwingIndex">reference</a>.</p>