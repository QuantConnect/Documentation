<p>To create an automatic indicators for <code>Counterattack</code>, call the <code class='csharp'>Counterattack</code><code class='python'>counterattack</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>Counterattack</code><code class='python'>counterattack</code> method creates a <code>Counterattack</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class CounterattackAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private Counterattack _counterattack;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _counterattack = CandlestickPatterns.Counterattack(_symbol);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_counterattack.IsReady)
        &lcub;
            // The current value of _counterattack is represented by itself (_counterattack)
            // or _counterattack.Current.Value
            Plot("Counterattack", "counterattack", _counterattack);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class CounterattackAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._counterattack = self.candlestick_patterns.counterattack(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._counterattack.is_ready:
            # The current value of self._counterattack is represented by self._counterattack.current.value
            self.plot("Counterattack", "counterattack", self._counterattack.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.counterattack">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>Counterattack</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class CounterattackAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private Counterattack _counterattack;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _counterattack = new Counterattack();
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _counterattack.Update(bar);

        if (_counterattack.IsReady)
        &lcub;
            // The current value of _counterattack is represented by itself (_counterattack)
            // or _counterattack.Current.Value
            Plot("Counterattack", "counterattack", _counterattack);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class CounterattackAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._counterattack = Counterattack()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._counterattack.update(bar)

        if self._counterattack.is_ready:
            # The current value of self._counterattack is represented by self._counterattack.current.value
            self.plot("Counterattack", "counterattack", self._counterattack.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1Counterattack.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/Counterattack">reference</a>.</p>