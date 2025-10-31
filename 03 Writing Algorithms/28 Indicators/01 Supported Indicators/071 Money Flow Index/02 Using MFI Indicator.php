<p>To create an automatic indicators for <code>MoneyFlowIndex</code>, call the <code class='csharp'>MFI</code><code class='python'>mfi</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>MFI</code><code class='python'>mfi</code> method creates a <code>MoneyFlowIndex</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class MoneyFlowIndexAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private MoneyFlowIndex _mfi;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _mfi = MFI(_symbol, 20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_mfi.IsReady)
        &lcub;
            // The current value of _mfi is represented by itself (_mfi)
            // or _mfi.Current.Value
            Plot("MoneyFlowIndex", "mfi", _mfi);
            // Plot all properties of abands
            Plot("MoneyFlowIndex", "positivemoneyflow", _mfi.PositiveMoneyFlow);
            Plot("MoneyFlowIndex", "negativemoneyflow", _mfi.NegativeMoneyFlow);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class MoneyFlowIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._mfi = self.mfi(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:
        if self._mfi.is_ready:
            # The current value of self._mfi is represented by self._mfi.current.value
            self.plot("MoneyFlowIndex", "mfi", self._mfi.current.value)
            # Plot all attributes of self._mfi
            self.plot("MoneyFlowIndex", "positive_money_flow", self._mfi.positive_money_flow.current.value)
            self.plot("MoneyFlowIndex", "negative_money_flow", self._mfi.negative_money_flow.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.mfi">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>MoneyFlowIndex</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class MoneyFlowIndexAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private MoneyFlowIndex _moneyflowindex;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _moneyflowindex = new MoneyFlowIndex(20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _moneyflowindex.Update(bar.EndTime, bar.Close);
        if (_moneyflowindex.IsReady)
        &lcub;
            // The current value of _moneyflowindex is represented by itself (_moneyflowindex)
            // or _moneyflowindex.Current.Value
            Plot("MoneyFlowIndex", "moneyflowindex", _moneyflowindex);
            // Plot all properties of abands
            Plot("MoneyFlowIndex", "positivemoneyflow", _moneyflowindex.PositiveMoneyFlow);
            Plot("MoneyFlowIndex", "negativemoneyflow", _moneyflowindex.NegativeMoneyFlow);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class MoneyFlowIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._moneyflowindex = MoneyFlowIndex(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._moneyflowindex.update(bar.end_time, bar.close)
        if self._moneyflowindex.is_ready:
            # The current value of self._moneyflowindex is represented by self._moneyflowindex.current.value
            self.plot("MoneyFlowIndex", "moneyflowindex", self._moneyflowindex.current.value)
            # Plot all attributes of self._moneyflowindex
            self.plot("MoneyFlowIndex", "positive_money_flow", self._moneyflowindex.positive_money_flow.current.value)
            self.plot("MoneyFlowIndex", "negative_money_flow", self._moneyflowindex.negative_money_flow.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1MoneyFlowIndex.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/MoneyFlowIndex">reference</a>.</p>