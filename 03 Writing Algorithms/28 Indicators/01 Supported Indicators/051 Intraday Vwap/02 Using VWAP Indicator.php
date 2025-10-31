<p>To create an automatic indicators for <code>IntradayVwap</code>, call the <code class='csharp'>VWAP</code><code class='python'>vwap</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>VWAP</code><code class='python'>vwap</code> method creates a <code>IntradayVwap</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class IntradayVwapAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private IntradayVwap _vwap;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _vwap = VWAP(_symbol);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_vwap.IsReady)
        &lcub;
            // The current value of _vwap is represented by itself (_vwap)
            // or _vwap.Current.Value
            Plot("IntradayVwap", "vwap", _vwap);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class IntradayVwapAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._vwap = self.vwap(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._vwap.is_ready:
            # The current value of self._vwap is represented by self._vwap.current.value
            self.plot("IntradayVwap", "vwap", self._vwap.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.vwap">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>IntradayVwap</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class IntradayVwapAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private IntradayVwap _intradayvwap;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _intradayvwap = new IntradayVwap("SPY");
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _intradayvwap.Update(bar.EndTime, bar.Close);
        if (_intradayvwap.IsReady)
        &lcub;
            // The current value of _intradayvwap is represented by itself (_intradayvwap)
            // or _intradayvwap.Current.Value
            Plot("IntradayVwap", "intradayvwap", _intradayvwap);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class IntradayVwapAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._intradayvwap = IntradayVwap("SPY")

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._intradayvwap.update(bar.end_time, bar.close)
        if self._intradayvwap.is_ready:
            # The current value of self._intradayvwap is represented by self._intradayvwap.current.value
            self.plot("IntradayVwap", "intradayvwap", self._intradayvwap.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1IntradayVwap.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/IntradayVwap">reference</a>.</p>