<p>To create an automatic indicators for <code>Stochastic</code>, call the <code class='csharp'>STO</code><code class='python'>sto</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>STO</code><code class='python'>sto</code> method creates a <code>Stochastic</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class StochasticAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private Stochastic _sto;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _sto = STO(_symbol, 20, 10, 20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;

        if (_sto.IsReady)
        &lcub;
            // The current value of _sto is represented by itself (_sto)
            // or _sto.Current.Value
            Plot("Stochastic", "sto", _sto);
            // Plot all properties of abands
            Plot("Stochastic", "faststoch", _sto.FastStoch);
            Plot("Stochastic", "stochk", _sto.StochK);
            Plot("Stochastic", "stochd", _sto.StochD);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class StochasticAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._sto = self.sto(self._symbol, 20, 10, 20)

    def on_data(self, slice: Slice) -> None:

        if self._sto.is_ready:
            # The current value of self._sto is represented by self._sto.current.value
            self.plot("Stochastic", "sto", self._sto.current.value)
            # Plot all attributes of self._sto
            self.plot("Stochastic", "fast_stoch", self._sto.fast_stoch.current.value)
            self.plot("Stochastic", "stoch_k", self._sto.stoch_k.current.value)
            self.plot("Stochastic", "stoch_d", self._sto.stoch_d.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.sto">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>Stochastic</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class StochasticAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private Stochastic _stochastic;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _stochastic = new Stochastic(20, 10, 20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _stochastic.Update(bar.EndTime, bar.Close);

        if (_stochastic.IsReady)
        &lcub;
            // The current value of _stochastic is represented by itself (_stochastic)
            // or _stochastic.Current.Value
            Plot("Stochastic", "stochastic", _stochastic);
            // Plot all properties of abands
            Plot("Stochastic", "faststoch", _stochastic.FastStoch);
            Plot("Stochastic", "stochk", _stochastic.StochK);
            Plot("Stochastic", "stochd", _stochastic.StochD);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class StochasticAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._stochastic = Stochastic(20, 10, 20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._stochastic.update(bar.end_time, bar.close)

        if self._stochastic.is_ready:
            # The current value of self._stochastic is represented by self._stochastic.current.value
            self.plot("Stochastic", "stochastic", self._stochastic.current.value)
            # Plot all attributes of self._stochastic
            self.plot("Stochastic", "fast_stoch", self._stochastic.fast_stoch.current.value)
            self.plot("Stochastic", "stoch_k", self._stochastic.stoch_k.current.value)
            self.plot("Stochastic", "stoch_d", self._stochastic.stoch_d.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1Stochastic.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/Stochastic">reference</a>.</p>