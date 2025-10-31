<p>To create an automatic indicators for <code>Hammer</code>, call the <code class='csharp'>Hammer</code><code class='python'>hammer</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>Hammer</code><code class='python'>hammer</code> method creates a <code>Hammer</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class HammerAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private Hammer _hammer;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _hammer = CandlestickPatterns.Hammer(_symbol);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_hammer.IsReady)
        &lcub;
            // The current value of _hammer is represented by itself (_hammer)
            // or _hammer.Current.Value
            Plot("Hammer", "hammer", _hammer);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class HammerAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._hammer = self.candlestick_patterns.hammer(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._hammer.is_ready:
            # The current value of self._hammer is represented by self._hammer.current.value
            self.plot("Hammer", "hammer", self._hammer.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.hammer">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>Hammer</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class HammerAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private Hammer _hammer;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _hammer = new Hammer();
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _hammer.Update(bar);

        if (_hammer.IsReady)
        &lcub;
            // The current value of _hammer is represented by itself (_hammer)
            // or _hammer.Current.Value
            Plot("Hammer", "hammer", _hammer);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class HammerAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._hammer = Hammer()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._hammer.update(bar)

        if self._hammer.is_ready:
            # The current value of self._hammer is represented by self._hammer.current.value
            self.plot("Hammer", "hammer", self._hammer.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1Hammer.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/Hammer">reference</a>.</p>