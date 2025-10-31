<p>To create an automatic indicators for <code>Identity</code>, call the <code class='csharp'>Identity</code><code class='python'>identity</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>Identity</code><code class='python'>identity</code> method creates a <code>Identity</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class IdentityAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private Identity _identity;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _identity = Identity(_symbol);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_identity.IsReady)
        &lcub;
            // The current value of _identity is represented by itself (_identity)
            // or _identity.Current.Value
            Plot("Identity", "identity", _identity);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class IdentityAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._identity = self.identity(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._identity.is_ready:
            # The current value of self._identity is represented by self._identity.current.value
            self.plot("Identity", "identity", self._identity.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.identity">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>Identity</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class IdentityAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private Identity _identity;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _identity = new Identity("SPY");
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _identity.Update(bar.EndTime, bar.Close);
        if (_identity.IsReady)
        &lcub;
            // The current value of _identity is represented by itself (_identity)
            // or _identity.Current.Value
            Plot("Identity", "identity", _identity);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class IdentityAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._identity = Identity("SPY")

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._identity.update(bar.end_time, bar.close)
        if self._identity.is_ready:
            # The current value of self._identity is represented by self._identity.current.value
            self.plot("Identity", "identity", self._identity.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1Identity.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/Identity">reference</a>.</p>