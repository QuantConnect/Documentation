<p>To create an automatic indicators for <code>IdenticalThreeCrows</code>, call the <code class='csharp'>IdenticalThreeCrows</code><code class='python'>identical_three_crows</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>IdenticalThreeCrows</code><code class='python'>identical_three_crows</code> method creates a <code>IdenticalThreeCrows</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class IdenticalThreeCrowsAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private IdenticalThreeCrows _identicalThreeCrows;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _identicalThreeCrows = CandlestickPatterns.IdenticalThreeCrows(_symbol);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_identicalThreeCrows.IsReady)
        &lcub;
            // The current value of _identicalThreeCrows is represented by itself (_identicalThreeCrows)
            // or _identicalThreeCrows.Current.Value
            Plot("IdenticalThreeCrows", "identicalThreeCrows", _identicalThreeCrows);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class IdenticalThreeCrowsAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._identical_three_crows = self.candlestick_patterns.identical_three_crows(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._identical_three_crows.is_ready:
            # The current value of self._identical_three_crows is represented by self._identical_three_crows.current.value
            self.plot("IdenticalThreeCrows", "identical_three_crows", self._identical_three_crows.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.identical_three_crows">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>IdenticalThreeCrows</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class IdenticalThreeCrowsAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private IdenticalThreeCrows _identicalThreeCrows;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _identicalThreeCrows = new IdenticalThreeCrows();
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _identicalThreeCrows.Update(bar);

        if (_identicalThreeCrows.IsReady)
        &lcub;
            // The current value of _identicalThreeCrows is represented by itself (_identicalThreeCrows)
            // or _identicalThreeCrows.Current.Value
            Plot("IdenticalThreeCrows", "identicalThreeCrows", _identicalThreeCrows);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class IdenticalThreeCrowsAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._identical_three_crows = IdenticalThreeCrows()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._identical_three_crows.update(bar)

        if self._identical_three_crows.is_ready:
            # The current value of self._identical_three_crows is represented by self._identical_three_crows.current.value
            self.plot("IdenticalThreeCrows", "identical_three_crows", self._identical_three_crows.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1IdenticalThreeCrows.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/IdenticalThreeCrows">reference</a>.</p>