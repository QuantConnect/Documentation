<p>To create an automatic indicators for <code>EveningDojiStar</code>, call the <code class='csharp'>EveningDojiStar</code><code class='python'>evening_doji_star</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>EveningDojiStar</code><code class='python'>evening_doji_star</code> method creates a <code>EveningDojiStar</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class EveningDojiStarAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private EveningDojiStar _eveningDojiStar;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _eveningDojiStar = CandlestickPatterns.EveningDojiStar(_symbol);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_eveningDojiStar.IsReady)
        &lcub;
            // The current value of _eveningDojiStar is represented by itself (_eveningDojiStar)
            // or _eveningDojiStar.Current.Value
            Plot("EveningDojiStar", "eveningDojiStar", _eveningDojiStar);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class EveningDojiStarAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._evening_doji_star = self.candlestick_patterns.evening_doji_star(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._evening_doji_star.is_ready:
            # The current value of self._evening_doji_star is represented by self._evening_doji_star.current.value
            self.plot("EveningDojiStar", "evening_doji_star", self._evening_doji_star.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.evening_doji_star">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>EveningDojiStar</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class EveningDojiStarAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private EveningDojiStar _eveningDojiStar;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _eveningDojiStar = new EveningDojiStar();
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _eveningDojiStar.Update(bar);

        if (_eveningDojiStar.IsReady)
        &lcub;
            // The current value of _eveningDojiStar is represented by itself (_eveningDojiStar)
            // or _eveningDojiStar.Current.Value
            Plot("EveningDojiStar", "eveningDojiStar", _eveningDojiStar);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class EveningDojiStarAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._evening_doji_star = EveningDojiStar()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._evening_doji_star.update(bar)

        if self._evening_doji_star.is_ready:
            # The current value of self._evening_doji_star is represented by self._evening_doji_star.current.value
            self.plot("EveningDojiStar", "evening_doji_star", self._evening_doji_star.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1EveningDojiStar.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/EveningDojiStar">reference</a>.</p>