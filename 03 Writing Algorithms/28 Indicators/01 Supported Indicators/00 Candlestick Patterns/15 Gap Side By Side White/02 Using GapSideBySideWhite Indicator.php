<p>To create an automatic indicators for <code>GapSideBySideWhite</code>, call the <code class='csharp'>GapSideBySideWhite</code><code class='python'>gap_side_by_side_white</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>GapSideBySideWhite</code><code class='python'>gap_side_by_side_white</code> method creates a <code>GapSideBySideWhite</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class GapSideBySideWhiteAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private GapSideBySideWhite _gapSideBySideWhite;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _gapSideBySideWhite = CandlestickPatterns.GapSideBySideWhite(_symbol);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_gapSideBySideWhite.IsReady)
        &lcub;
            // The current value of _gapSideBySideWhite is represented by itself (_gapSideBySideWhite)
            // or _gapSideBySideWhite.Current.Value
            Plot("GapSideBySideWhite", "gapSideBySideWhite", _gapSideBySideWhite);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class GapSideBySideWhiteAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._gap_side_by_side_white = self.candlestick_patterns.gap_side_by_side_white(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._gap_side_by_side_white.is_ready:
            # The current value of self._gap_side_by_side_white is represented by self._gap_side_by_side_white.current.value
            self.plot("GapSideBySideWhite", "gap_side_by_side_white", self._gap_side_by_side_white.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.gap_side_by_side_white">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>GapSideBySideWhite</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class GapSideBySideWhiteAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private GapSideBySideWhite _gapSideBySideWhite;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _gapSideBySideWhite = new GapSideBySideWhite();
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _gapSideBySideWhite.Update(bar);

        if (_gapSideBySideWhite.IsReady)
        &lcub;
            // The current value of _gapSideBySideWhite is represented by itself (_gapSideBySideWhite)
            // or _gapSideBySideWhite.Current.Value
            Plot("GapSideBySideWhite", "gapSideBySideWhite", _gapSideBySideWhite);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class GapSideBySideWhiteAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._gap_side_by_side_white = GapSideBySideWhite()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._gap_side_by_side_white.update(bar)

        if self._gap_side_by_side_white.is_ready:
            # The current value of self._gap_side_by_side_white is represented by self._gap_side_by_side_white.current.value
            self.plot("GapSideBySideWhite", "gap_side_by_side_white", self._gap_side_by_side_white.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1GapSideBySideWhite.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/GapSideBySideWhite">reference</a>.</p>