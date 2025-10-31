<p>To create an automatic indicators for <code>SharpeRatio</code>, call the <code class='csharp'>SR</code><code class='python'>sr</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>SR</code><code class='python'>sr</code> method creates a <code>SharpeRatio</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class SharpeRatioAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private SharpeRatio _sr;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _sr = SR(_symbol, 22, 0.03m);
    &rcub;

    public override void OnData(Slice data)
    &lcub;

        if (_sr.IsReady)
        &lcub;
            // The current value of _sr is represented by itself (_sr)
            // or _sr.Current.Value
            Plot("SharpeRatio", "sr", _sr);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class SharpeRatioAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._sr = self.sr(self._symbol, 22, 0.03)

    def on_data(self, slice: Slice) -> None:

        if self._sr.is_ready:
            # The current value of self._sr is represented by self._sr.current.value
            self.plot("SharpeRatio", "sr", self._sr.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.sr">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>SharpeRatio</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class SharpeRatioAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private SharpeRatio _sharperatio;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _sharperatio = new SharpeRatio(22, 0.03);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _sharperatio.Update(bar.EndTime, bar.Close);

        if (_sharperatio.IsReady)
        &lcub;
            // The current value of _sharperatio is represented by itself (_sharperatio)
            // or _sharperatio.Current.Value
            Plot("SharpeRatio", "sharperatio", _sharperatio);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class SharpeRatioAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._sharperatio = SharpeRatio(22, 0.03)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._sharperatio.update(bar.end_time, bar.close)

        if self._sharperatio.is_ready:
            # The current value of self._sharperatio is represented by self._sharperatio.current.value
            self.plot("SharpeRatio", "sharperatio", self._sharperatio.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1SharpeRatio.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/SharpeRatio">reference</a>.</p>