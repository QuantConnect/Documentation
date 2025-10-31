<p>To create an automatic indicators for <code>MorningStar</code>, call the <code class='csharp'>MorningStar</code><code class='python'>morning_star</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>MorningStar</code><code class='python'>morning_star</code> method creates a <code>MorningStar</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class MorningStarAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private MorningStar _morningStar;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _morningStar = CandlestickPatterns.MorningStar(_symbol);
    }

    public override void OnData(Slice data)
    {
        if (_morningStar.IsReady)
        {
            // The current value of _morningStar is represented by itself (_morningStar)
            // or _morningStar.Current.Value
            Plot("MorningStar", "morningStar", _morningStar);
        }
    }
}</pre>
<pre class="python">class MorningStarAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._morning_star = self.candlestick_patterns.morning_star(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._morning_star.is_ready:
            # The current value of self._morning_star is represented by self._morning_star.current.value
            self.plot("MorningStar", "morning_star", self._morning_star.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.morning_star">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>MorningStar</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class MorningStarAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private MorningStar _morningStar;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _morningStar = new MorningStar();
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _morningStar.Update(bar);

        if (_morningStar.IsReady)
        {
            // The current value of _morningStar is represented by itself (_morningStar)
            // or _morningStar.Current.Value
            Plot("MorningStar", "morningStar", _morningStar);
        }
    }
}</pre>
<pre class="python">class MorningStarAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._morning_star = MorningStar()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._morning_star.update(bar)

        if self._morning_star.is_ready:
            # The current value of self._morning_star is represented by self._morning_star.current.value
            self.plot("MorningStar", "morning_star", self._morning_star.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1MorningStar.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/MorningStar">reference</a>.</p>