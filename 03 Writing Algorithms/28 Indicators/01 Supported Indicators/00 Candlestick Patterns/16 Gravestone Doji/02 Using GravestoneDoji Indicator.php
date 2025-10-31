<p>To create an automatic indicators for <code>GravestoneDoji</code>, call the <code class='csharp'>GravestoneDoji</code><code class='python'>gravestone_doji</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>GravestoneDoji</code><code class='python'>gravestone_doji</code> method creates a <code>GravestoneDoji</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class GravestoneDojiAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private GravestoneDoji _gravestoneDoji;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _gravestoneDoji = CandlestickPatterns.GravestoneDoji(_symbol);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_gravestoneDoji.IsReady)
        &lcub;
            // The current value of _gravestoneDoji is represented by itself (_gravestoneDoji)
            // or _gravestoneDoji.Current.Value
            Plot("GravestoneDoji", "gravestoneDoji", _gravestoneDoji);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class GravestoneDojiAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._gravestone_doji = self.candlestick_patterns.gravestone_doji(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._gravestone_doji.is_ready:
            # The current value of self._gravestone_doji is represented by self._gravestone_doji.current.value
            self.plot("GravestoneDoji", "gravestone_doji", self._gravestone_doji.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.gravestone_doji">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>GravestoneDoji</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class GravestoneDojiAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private GravestoneDoji _gravestoneDoji;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _gravestoneDoji = new GravestoneDoji();
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _gravestoneDoji.Update(bar);

        if (_gravestoneDoji.IsReady)
        &lcub;
            // The current value of _gravestoneDoji is represented by itself (_gravestoneDoji)
            // or _gravestoneDoji.Current.Value
            Plot("GravestoneDoji", "gravestoneDoji", _gravestoneDoji);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class GravestoneDojiAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._gravestone_doji = GravestoneDoji()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._gravestone_doji.update(bar)

        if self._gravestone_doji.is_ready:
            # The current value of self._gravestone_doji is represented by self._gravestone_doji.current.value
            self.plot("GravestoneDoji", "gravestone_doji", self._gravestone_doji.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1GravestoneDoji.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/GravestoneDoji">reference</a>.</p>