<p>To create an automatic indicators for <code>LongLeggedDoji</code>, call the <code class='csharp'>LongLeggedDoji</code><code class='python'>long_legged_doji</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>LongLeggedDoji</code><code class='python'>long_legged_doji</code> method creates a <code>LongLeggedDoji</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class LongLeggedDojiAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private LongLeggedDoji _longLeggedDoji;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _longLeggedDoji = CandlestickPatterns.LongLeggedDoji(_symbol);
    }

    public override void OnData(Slice data)
    {
        if (_longLeggedDoji.IsReady)
        {
            // The current value of _longLeggedDoji is represented by itself (_longLeggedDoji)
            // or _longLeggedDoji.Current.Value
            Plot("LongLeggedDoji", "longLeggedDoji", _longLeggedDoji);
        }
    }
}</pre>
<pre class="python">class LongLeggedDojiAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._long_legged_doji = self.candlestick_patterns.long_legged_doji(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._long_legged_doji.is_ready:
            # The current value of self._long_legged_doji is represented by self._long_legged_doji.current.value
            self.plot("LongLeggedDoji", "long_legged_doji", self._long_legged_doji.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.long_legged_doji">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>LongLeggedDoji</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class LongLeggedDojiAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private LongLeggedDoji _longLeggedDoji;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _longLeggedDoji = new LongLeggedDoji();
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _longLeggedDoji.Update(bar);

        if (_longLeggedDoji.IsReady)
        {
            // The current value of _longLeggedDoji is represented by itself (_longLeggedDoji)
            // or _longLeggedDoji.Current.Value
            Plot("LongLeggedDoji", "longLeggedDoji", _longLeggedDoji);
        }
    }
}</pre>
<pre class="python">class LongLeggedDojiAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._long_legged_doji = LongLeggedDoji()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._long_legged_doji.update(bar)

        if self._long_legged_doji.is_ready:
            # The current value of self._long_legged_doji is represented by self._long_legged_doji.current.value
            self.plot("LongLeggedDoji", "long_legged_doji", self._long_legged_doji.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1LongLeggedDoji.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/LongLeggedDoji">reference</a>.</p>