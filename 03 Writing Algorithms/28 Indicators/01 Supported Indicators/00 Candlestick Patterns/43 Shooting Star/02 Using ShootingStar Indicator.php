<p>To create an automatic indicators for <code>ShootingStar</code>, call the <code class='csharp'>ShootingStar</code><code class='python'>shooting_star</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>ShootingStar</code><code class='python'>shooting_star</code> method creates a <code>ShootingStar</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class ShootingStarAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private ShootingStar _shootingStar;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _shootingStar = CandlestickPatterns.ShootingStar(_symbol);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_shootingStar.IsReady)
        &lcub;
            // The current value of _shootingStar is represented by itself (_shootingStar)
            // or _shootingStar.Current.Value
            Plot("ShootingStar", "shootingStar", _shootingStar);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class ShootingStarAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._shooting_star = self.candlestick_patterns.shooting_star(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._shooting_star.is_ready:
            # The current value of self._shooting_star is represented by self._shooting_star.current.value
            self.plot("ShootingStar", "shooting_star", self._shooting_star.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1CandlestickPatterns.html">CandlestickPatterns class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/CandlestickPatterns/#QuantConnect.Algorithm.CandlestickPatterns.shooting_star">CandlestickPatterns class</a>.</p>
<p>You can manually create a <code>ShootingStar</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method with a <code>TradeBar</code>. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class ShootingStarAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private ShootingStar _shootingStar;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _shootingStar = new ShootingStar();
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _shootingStar.Update(bar);

        if (_shootingStar.IsReady)
        &lcub;
            // The current value of _shootingStar is represented by itself (_shootingStar)
            // or _shootingStar.Current.Value
            Plot("ShootingStar", "shootingStar", _shootingStar);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class ShootingStarAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._shooting_star = ShootingStar()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._shooting_star.update(bar)

        if self._shooting_star.is_ready:
            # The current value of self._shooting_star is represented by self._shooting_star.current.value
            self.plot("ShootingStar", "shooting_star", self._shooting_star.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CandlestickPatterns_1_1ShootingStar.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CandlestickPatterns/ShootingStar">reference</a>.</p>