<p>To create an automatic indicators for <code>EveningStar</code>, call the <code class='csharp'>EveningStar</code><code class='python'>evening_star</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>EveningStar</code><code class='python'>evening_star</code> method creates a <code>EveningStar</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class EveningStarAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private EveningStar _eveningStar;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _eveningStar = CandlestickPatterns.EveningStar(_symbol);
    }

    public override void OnData(Slice data)
    {
        if (_eveningStar.IsReady)
        {
            // The current value of _eveningStar is represented by itself (_eveningStar)
            // or _eveningStar.Current.Value
            Plot("EveningStar", "eveningStar", _eveningStar);
        }
    }
}</pre>
<pre class="python">class EveningStarAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._evening_star = self.candlestick_patterns.evening_star(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._evening_star.is_ready:
            # The current value of self._evening_star is represented by self._evening_star.current.value
            self.plot("EveningStar", "evening_star", self._evening_star.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.evening_star">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>EveningStar</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class EveningStarAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private EveningStar _eveningStar;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _eveningStar = new EveningStar();
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _eveningStar.Update(bar);

        if (_eveningStar.IsReady)
        {
            // The current value of _eveningStar is represented by itself (_eveningStar)
            // or _eveningStar.Current.Value
            Plot("EveningStar", "eveningStar", _eveningStar);
        }
    }
}</pre>
<pre class="python">class EveningStarAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._evening_star = EveningStar()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._evening_star.update(bar)

        if self._evening_star.is_ready:
            # The current value of self._evening_star is represented by self._evening_star.current.value
            self.plot("EveningStar", "evening_star", self._evening_star.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1EveningStar.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/EveningStar">reference</a>.</p>