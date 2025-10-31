<p>To create an automatic indicators for <code>TripleExponentialMovingAverage</code>, call the <code class='csharp'>TEMA</code><code class='python'>tema</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>TEMA</code><code class='python'>tema</code> method creates a <code>TripleExponentialMovingAverage</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class TripleExponentialMovingAverageAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private TripleExponentialMovingAverage _tema;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _tema = TEMA(_symbol, 20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;

        if (_tema.IsReady)
        &lcub;
            // The current value of _tema is represented by itself (_tema)
            // or _tema.Current.Value
            Plot("TripleExponentialMovingAverage", "tema", _tema);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class TripleExponentialMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._tema = self.tema(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:

        if self._tema.is_ready:
            # The current value of self._tema is represented by self._tema.current.value
            self.plot("TripleExponentialMovingAverage", "tema", self._tema.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.tema">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>TripleExponentialMovingAverage</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class TripleExponentialMovingAverageAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private TripleExponentialMovingAverage _tripleexponentialmovingaverage;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _tripleexponentialmovingaverage = new TripleExponentialMovingAverage(20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _tripleexponentialmovingaverage.Update(bar.EndTime, bar.Close);

        if (_tripleexponentialmovingaverage.IsReady)
        &lcub;
            // The current value of _tripleexponentialmovingaverage is represented by itself (_tripleexponentialmovingaverage)
            // or _tripleexponentialmovingaverage.Current.Value
            Plot("TripleExponentialMovingAverage", "tripleexponentialmovingaverage", _tripleexponentialmovingaverage);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class TripleExponentialMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._tripleexponentialmovingaverage = TripleExponentialMovingAverage(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._tripleexponentialmovingaverage.update(bar.end_time, bar.close)

        if self._tripleexponentialmovingaverage.is_ready:
            # The current value of self._tripleexponentialmovingaverage is represented by self._tripleexponentialmovingaverage.current.value
            self.plot("TripleExponentialMovingAverage", "tripleexponentialmovingaverage", self._tripleexponentialmovingaverage.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1TripleExponentialMovingAverage.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/TripleExponentialMovingAverage">reference</a>.</p>