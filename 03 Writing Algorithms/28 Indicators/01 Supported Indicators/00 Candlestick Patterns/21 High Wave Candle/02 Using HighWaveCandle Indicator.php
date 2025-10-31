<p>To create an automatic indicators for <code>HighWaveCandle</code>, call the <code class='csharp'>HighWaveCandle</code><code class='python'>high_wave_candle</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>HighWaveCandle</code><code class='python'>high_wave_candle</code> method creates a <code>HighWaveCandle</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class HighWaveCandleAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private HighWaveCandle _highWaveCandle;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _highWaveCandle = CandlestickPatterns.HighWaveCandle(_symbol);
    }

    public override void OnData(Slice data)
    {
        if (_highWaveCandle.IsReady)
        {
            // The current value of _highWaveCandle is represented by itself (_highWaveCandle)
            // or _highWaveCandle.Current.Value
            Plot("HighWaveCandle", "highWaveCandle", _highWaveCandle);
        }
    }
}</pre>
<pre class="python">class HighWaveCandleAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._high_wave_candle = self.candlestick_patterns.high_wave_candle(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._high_wave_candle.is_ready:
            # The current value of self._high_wave_candle is represented by self._high_wave_candle.current.value
            self.plot("HighWaveCandle", "high_wave_candle", self._high_wave_candle.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.high_wave_candle">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>HighWaveCandle</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class HighWaveCandleAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private HighWaveCandle _highWaveCandle;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _highWaveCandle = new HighWaveCandle();
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _highWaveCandle.Update(bar);

        if (_highWaveCandle.IsReady)
        {
            // The current value of _highWaveCandle is represented by itself (_highWaveCandle)
            // or _highWaveCandle.Current.Value
            Plot("HighWaveCandle", "highWaveCandle", _highWaveCandle);
        }
    }
}</pre>
<pre class="python">class HighWaveCandleAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._high_wave_candle = HighWaveCandle()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._high_wave_candle.update(bar)

        if self._high_wave_candle.is_ready:
            # The current value of self._high_wave_candle is represented by self._high_wave_candle.current.value
            self.plot("HighWaveCandle", "high_wave_candle", self._high_wave_candle.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1HighWaveCandle.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/HighWaveCandle">reference</a>.</p>