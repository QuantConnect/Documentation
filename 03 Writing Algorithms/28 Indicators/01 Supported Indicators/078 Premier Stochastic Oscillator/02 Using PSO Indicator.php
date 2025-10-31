<p>To create an automatic indicators for <code>PremierStochasticOscillator</code>, call the <code class='csharp'>PSO</code><code class='python'>pso</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>PSO</code><code class='python'>pso</code> method creates a <code>PremierStochasticOscillator</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class PremierStochasticOscillatorAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private PremierStochasticOscillator _pso;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _pso = PSO(_symbol, 14, 3);
    &rcub;

    public override void OnData(Slice data)
    &lcub;

        if (_pso.IsReady)
        &lcub;
            // The current value of _pso is represented by itself (_pso)
            // or _pso.Current.Value
            Plot("PremierStochasticOscillator", "pso", _pso);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class PremierStochasticOscillatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._pso = self.pso(self._symbol, 14, 3)

    def on_data(self, slice: Slice) -> None:

        if self._pso.is_ready:
            # The current value of self._pso is represented by self._pso.current.value
            self.plot("PremierStochasticOscillator", "pso", self._pso.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.pso">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>PremierStochasticOscillator</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class PremierStochasticOscillatorAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private PremierStochasticOscillator _premierstochasticoscillator;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _premierstochasticoscillator = new PremierStochasticOscillator(14, 3);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _premierstochasticoscillator.Update(bar.EndTime, bar.Close);

        if (_premierstochasticoscillator.IsReady)
        &lcub;
            // The current value of _premierstochasticoscillator is represented by itself (_premierstochasticoscillator)
            // or _premierstochasticoscillator.Current.Value
            Plot("PremierStochasticOscillator", "premierstochasticoscillator", _premierstochasticoscillator);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class PremierStochasticOscillatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._premierstochasticoscillator = PremierStochasticOscillator(14, 3)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._premierstochasticoscillator.update(bar.end_time, bar.close)

        if self._premierstochasticoscillator.is_ready:
            # The current value of self._premierstochasticoscillator is represented by self._premierstochasticoscillator.current.value
            self.plot("PremierStochasticOscillator", "premierstochasticoscillator", self._premierstochasticoscillator.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1PremierStochasticOscillator.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/PremierStochasticOscillator">reference</a>.</p>