<p>To create an automatic indicators for <code>DerivativeOscillator</code>, call the <code class='csharp'>DO</code><code class='python'>do</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>DO</code><code class='python'>do</code> method creates a <code>DerivativeOscillator</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class DerivativeOscillatorAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private DerivativeOscillator _do;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _do = DO(_symbol, 14, 5, 3, 9);
    &rcub;

    public override void OnData(Slice data)
    &lcub;

        if (_do.IsReady)
        &lcub;
            // The current value of _do is represented by itself (_do)
            // or _do.Current.Value
            Plot("DerivativeOscillator", "do", _do);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class DerivativeOscillatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._do = self.do(self._symbol, 14, 5, 3, 9)

    def on_data(self, slice: Slice) -> None:

        if self._do.is_ready:
            # The current value of self._do is represented by self._do.current.value
            self.plot("DerivativeOscillator", "do", self._do.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.do">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>DerivativeOscillator</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class DerivativeOscillatorAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private DerivativeOscillator _derivativeoscillator;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _derivativeoscillator = new DerivativeOscillator("DO", 14, 5, 3, 9);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _derivativeoscillator.Update(bar.EndTime, bar.Close);

        if (_derivativeoscillator.IsReady)
        &lcub;
            // The current value of _derivativeoscillator is represented by itself (_derivativeoscillator)
            // or _derivativeoscillator.Current.Value
            Plot("DerivativeOscillator", "derivativeoscillator", _derivativeoscillator);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class DerivativeOscillatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._derivativeoscillator = DerivativeOscillator("DO", 14, 5, 3, 9)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._derivativeoscillator.update(bar.end_time, bar.close)

        if self._derivativeoscillator.is_ready:
            # The current value of self._derivativeoscillator is represented by self._derivativeoscillator.current.value
            self.plot("DerivativeOscillator", "derivativeoscillator", self._derivativeoscillator.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1DerivativeOscillator.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/DerivativeOscillator">reference</a>.</p>