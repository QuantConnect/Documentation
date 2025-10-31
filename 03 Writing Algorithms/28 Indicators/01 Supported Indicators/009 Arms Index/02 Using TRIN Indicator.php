<p>To create an automatic indicators for <code>ArmsIndex</code>, call the <code class='csharp'>TRIN</code><code class='python'>tring</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>TRIN</code><code class='python'>tring</code> method creates a <code>ArmsIndex</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class ArmsIndexAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol,_reference;
    private ArmsIndex _trin;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("QQQ", Resolution.Daily).Symbol;
        _reference = AddEquity("SPY", Resolution.Daily).Symbol;
        _trin = TRIN([_symbol, _reference]);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_trin.IsReady)
        &lcub;
            // The current value of _trin is represented by itself (_trin)
            // or _trin.Current.Value
            Plot("ArmsIndex", "trin", _trin);
            // Plot all properties of abands
            Plot("ArmsIndex", "adratio", _trin.AdRatio);
            Plot("ArmsIndex", "advratio", _trin.AdvRatio);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class ArmsIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("QQQ", Resolution.DAILY).symbol
        self._reference = self.add_equity("SPY", Resolution.DAILY).symbol
        self._tring = self.tring([self._symbol, self._reference])

    def on_data(self, slice: Slice) -> None:
        if self._tring.is_ready:
            # The current value of self._tring is represented by self._tring.current.value
            self.plot("ArmsIndex", "tring", self._tring.current.value)
            # Plot all attributes of self._tring
            self.plot("ArmsIndex", "ad_ratio", self._tring.ad_ratio.current.value)
            self.plot("ArmsIndex", "adv_ratio", self._tring.adv_ratio.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.tring">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>ArmsIndex</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class ArmsIndexAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private ArmsIndex _armsindex;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _armsindex = new ArmsIndex("");
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _armsindex.Update(bar.EndTime, bar.Close);
        if (_armsindex.IsReady)
        &lcub;
            // The current value of _armsindex is represented by itself (_armsindex)
            // or _armsindex.Current.Value
            Plot("ArmsIndex", "armsindex", _armsindex);
            // Plot all properties of abands
            Plot("ArmsIndex", "adratio", _armsindex.AdRatio);
            Plot("ArmsIndex", "advratio", _armsindex.AdvRatio);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class ArmsIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._armsindex = ArmsIndex("")

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._armsindex.update(bar.end_time, bar.close)
        if self._armsindex.is_ready:
            # The current value of self._armsindex is represented by self._armsindex.current.value
            self.plot("ArmsIndex", "armsindex", self._armsindex.current.value)
            # Plot all attributes of self._armsindex
            self.plot("ArmsIndex", "ad_ratio", self._armsindex.ad_ratio.current.value)
            self.plot("ArmsIndex", "adv_ratio", self._armsindex.adv_ratio.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1ArmsIndex.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/ArmsIndex">reference</a>.</p>