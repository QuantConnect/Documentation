<p>To create an automatic indicators for <code>Breakaway</code>, call the <code class='csharp'>Breakaway</code><code class='python'>breakaway</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>Breakaway</code><code class='python'>breakaway</code> method creates a <code>Breakaway</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class BreakawayAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private Breakaway _breakaway;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _breakaway = CandlestickPatterns.Breakaway(_symbol);
    }

    public override void OnData(Slice data)
    {
        if (_breakaway.IsReady)
        {
            // The current value of _breakaway is represented by itself (_breakaway)
            // or _breakaway.Current.Value
            Plot("Breakaway", "breakaway", _breakaway);
        }
    }
}</pre>
<pre class="python">class BreakawayAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._breakaway = self.candlestick_patterns.breakaway(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._breakaway.is_ready:
            # The current value of self._breakaway is represented by self._breakaway.current.value
            self.plot("Breakaway", "breakaway", self._breakaway.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.breakaway">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>Breakaway</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class BreakawayAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private Breakaway _breakaway;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _breakaway = new Breakaway();
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _breakaway.Update(bar);

        if (_breakaway.IsReady)
        {
            // The current value of _breakaway is represented by itself (_breakaway)
            // or _breakaway.Current.Value
            Plot("Breakaway", "breakaway", _breakaway);
        }
    }
}</pre>
<pre class="python">class BreakawayAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._breakaway = Breakaway()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._breakaway.update(bar)

        if self._breakaway.is_ready:
            # The current value of self._breakaway is represented by self._breakaway.current.value
            self.plot("Breakaway", "breakaway", self._breakaway.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1Breakaway.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/Breakaway">reference</a>.</p>