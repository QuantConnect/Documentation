<p>To create an automatic indicators for <code>BalanceOfPower</code>, call the <code class='csharp'>BOP</code><code class='python'>bop</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>BOP</code><code class='python'>bop</code> method creates a <code>BalanceOfPower</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class BalanceOfPowerAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private BalanceOfPower _bop;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _bop = BOP(_symbol);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_bop.IsReady)
        &lcub;
            // The current value of _bop is represented by itself (_bop)
            // or _bop.Current.Value
            Plot("BalanceOfPower", "bop", _bop);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class BalanceOfPowerAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._bop = self.bop(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._bop.is_ready:
            # The current value of self._bop is represented by self._bop.current.value
            self.plot("BalanceOfPower", "bop", self._bop.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.bop">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>BalanceOfPower</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class BalanceOfPowerAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private BalanceOfPower _balanceofpower;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _balanceofpower = new BalanceOfPower();
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _balanceofpower.Update(bar.EndTime, bar.Close);
        if (_balanceofpower.IsReady)
        &lcub;
            // The current value of _balanceofpower is represented by itself (_balanceofpower)
            // or _balanceofpower.Current.Value
            Plot("BalanceOfPower", "balanceofpower", _balanceofpower);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class BalanceOfPowerAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._balanceofpower = BalanceOfPower()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._balanceofpower.update(bar.end_time, bar.close)
        if self._balanceofpower.is_ready:
            # The current value of self._balanceofpower is represented by self._balanceofpower.current.value
            self.plot("BalanceOfPower", "balanceofpower", self._balanceofpower.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1BalanceOfPower.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/BalanceOfPower">reference</a>.</p>