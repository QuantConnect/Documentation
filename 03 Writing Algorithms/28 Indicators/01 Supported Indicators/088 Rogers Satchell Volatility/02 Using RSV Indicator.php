<p>To create an automatic indicators for <code>RogersSatchellVolatility</code>, call the <code class='csharp'>RSV</code><code class='python'>rsv</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>RSV</code><code class='python'>rsv</code> method creates a <code>RogersSatchellVolatility</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class RogersSatchellVolatilityAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private RogersSatchellVolatility _rsv;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _rsv = RSV(_symbol, 30);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_rsv.IsReady)
        &lcub;
            // The current value of _rsv is represented by itself (_rsv)
            // or _rsv.Current.Value
            Plot("RogersSatchellVolatility", "rsv", _rsv);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class RogersSatchellVolatilityAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._rsv = self.rsv(self._symbol, 30)

    def on_data(self, slice: Slice) -> None:
        if self._rsv.is_ready:
            # The current value of self._rsv is represented by self._rsv.current.value
            self.plot("RogersSatchellVolatility", "rsv", self._rsv.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.rsv">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>RogersSatchellVolatility</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class RogersSatchellVolatilityAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private RogersSatchellVolatility _rogerssatchellvolatility;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _rogerssatchellvolatility = new RogersSatchellVolatility(30);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _rogerssatchellvolatility.Update(bar.EndTime, bar.Close);
        if (_rogerssatchellvolatility.IsReady)
        &lcub;
            // The current value of _rogerssatchellvolatility is represented by itself (_rogerssatchellvolatility)
            // or _rogerssatchellvolatility.Current.Value
            Plot("RogersSatchellVolatility", "rogerssatchellvolatility", _rogerssatchellvolatility);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class RogersSatchellVolatilityAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._rogerssatchellvolatility = RogersSatchellVolatility(30)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._rogerssatchellvolatility.update(bar.end_time, bar.close)
        if self._rogerssatchellvolatility.is_ready:
            # The current value of self._rogerssatchellvolatility is represented by self._rogerssatchellvolatility.current.value
            self.plot("RogersSatchellVolatility", "rogerssatchellvolatility", self._rogerssatchellvolatility.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1RogersSatchellVolatility.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/RogersSatchellVolatility">reference</a>.</p>