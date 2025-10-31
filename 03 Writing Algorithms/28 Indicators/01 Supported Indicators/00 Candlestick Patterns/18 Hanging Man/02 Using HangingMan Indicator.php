<p>To create an automatic indicators for <code>HangingMan</code>, call the <code class='csharp'>HangingMan</code><code class='python'>hanging_man</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>HangingMan</code><code class='python'>hanging_man</code> method creates a <code>HangingMan</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class HangingManAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private HangingMan _hangingMan;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _hangingMan = CandlestickPatterns.HangingMan(_symbol);
    }

    public override void OnData(Slice data)
    {
        if (_hangingMan.IsReady)
        {
            // The current value of _hangingMan is represented by itself (_hangingMan)
            // or _hangingMan.Current.Value
            Plot("HangingMan", "hangingMan", _hangingMan);
        }
    }
}</pre>
<pre class="python">class HangingManAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._hanging_man = self.candlestick_patterns.hanging_man(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._hanging_man.is_ready:
            # The current value of self._hanging_man is represented by self._hanging_man.current.value
            self.plot("HangingMan", "hanging_man", self._hanging_man.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.hanging_man">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>HangingMan</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class HangingManAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private HangingMan _hangingMan;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _hangingMan = new HangingMan();
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _hangingMan.Update(bar);

        if (_hangingMan.IsReady)
        {
            // The current value of _hangingMan is represented by itself (_hangingMan)
            // or _hangingMan.Current.Value
            Plot("HangingMan", "hangingMan", _hangingMan);
        }
    }
}</pre>
<pre class="python">class HangingManAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._hanging_man = HangingMan()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._hanging_man.update(bar)

        if self._hanging_man.is_ready:
            # The current value of self._hanging_man is represented by self._hanging_man.current.value
            self.plot("HangingMan", "hanging_man", self._hanging_man.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1HangingMan.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/HangingMan">reference</a>.</p>