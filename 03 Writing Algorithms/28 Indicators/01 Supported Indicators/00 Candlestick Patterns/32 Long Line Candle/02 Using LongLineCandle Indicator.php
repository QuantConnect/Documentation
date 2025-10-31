<p>To create an automatic indicators for <code>LongLineCandle</code>, call the <code class='csharp'>LongLineCandle</code><code class='python'>long_line_candle</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>LongLineCandle</code><code class='python'>long_line_candle</code> method creates a <code>LongLineCandle</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class LongLineCandleAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private LongLineCandle _longLineCandle;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _longLineCandle = CandlestickPatterns.LongLineCandle(_symbol);
    }

    public override void OnData(Slice data)
    {
        if (_longLineCandle.IsReady)
        {
            // The current value of _longLineCandle is represented by itself (_longLineCandle)
            // or _longLineCandle.Current.Value
            Plot("LongLineCandle", "longLineCandle", _longLineCandle);
        }
    }
}</pre>
<pre class="python">class LongLineCandleAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._long_line_candle = self.candlestick_patterns.long_line_candle(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._long_line_candle.is_ready:
            # The current value of self._long_line_candle is represented by self._long_line_candle.current.value
            self.plot("LongLineCandle", "long_line_candle", self._long_line_candle.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.long_line_candle">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>LongLineCandle</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class LongLineCandleAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private LongLineCandle _longLineCandle;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _longLineCandle = new LongLineCandle();
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _longLineCandle.Update(bar);

        if (_longLineCandle.IsReady)
        {
            // The current value of _longLineCandle is represented by itself (_longLineCandle)
            // or _longLineCandle.Current.Value
            Plot("LongLineCandle", "longLineCandle", _longLineCandle);
        }
    }
}</pre>
<pre class="python">class LongLineCandleAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._long_line_candle = LongLineCandle()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._long_line_candle.update(bar)

        if self._long_line_candle.is_ready:
            # The current value of self._long_line_candle is represented by self._long_line_candle.current.value
            self.plot("LongLineCandle", "long_line_candle", self._long_line_candle.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1LongLineCandle.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/LongLineCandle">reference</a>.</p>