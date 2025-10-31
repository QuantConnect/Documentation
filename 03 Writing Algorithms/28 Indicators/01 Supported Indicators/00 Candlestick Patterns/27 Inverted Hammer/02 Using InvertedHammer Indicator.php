<p>To create an automatic indicators for <code>InvertedHammer</code>, call the <code class='csharp'>InvertedHammer</code><code class='python'>inverted_hammer</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>InvertedHammer</code><code class='python'>inverted_hammer</code> method creates a <code>InvertedHammer</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class InvertedHammerAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private InvertedHammer _invertedHammer;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _invertedHammer = CandlestickPatterns.InvertedHammer(_symbol);
    }

    public override void OnData(Slice data)
    {
        if (_invertedHammer.IsReady)
        {
            // The current value of _invertedHammer is represented by itself (_invertedHammer)
            // or _invertedHammer.Current.Value
            Plot("InvertedHammer", "invertedHammer", _invertedHammer);
        }
    }
}</pre>
<pre class="python">class InvertedHammerAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._inverted_hammer = self.candlestick_patterns.inverted_hammer(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._inverted_hammer.is_ready:
            # The current value of self._inverted_hammer is represented by self._inverted_hammer.current.value
            self.plot("InvertedHammer", "inverted_hammer", self._inverted_hammer.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.inverted_hammer">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>InvertedHammer</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class InvertedHammerAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private InvertedHammer _invertedHammer;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _invertedHammer = new InvertedHammer();
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _invertedHammer.Update(bar);

        if (_invertedHammer.IsReady)
        {
            // The current value of _invertedHammer is represented by itself (_invertedHammer)
            // or _invertedHammer.Current.Value
            Plot("InvertedHammer", "invertedHammer", _invertedHammer);
        }
    }
}</pre>
<pre class="python">class InvertedHammerAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._inverted_hammer = InvertedHammer()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._inverted_hammer.update(bar)

        if self._inverted_hammer.is_ready:
            # The current value of self._inverted_hammer is represented by self._inverted_hammer.current.value
            self.plot("InvertedHammer", "inverted_hammer", self._inverted_hammer.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1InvertedHammer.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/InvertedHammer">reference</a>.</p>