<p>To create an automatic indicators for <code>AwesomeOscillator</code>, call the <code class='csharp'>AO</code><code class='python'>ao</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>AO</code><code class='python'>ao</code> method creates a <code>AwesomeOscillator</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class AwesomeOscillatorAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private AwesomeOscillator _ao;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _ao = AO(_symbol, 10, 20, MovingAverageType.Simple);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_ao.IsReady)
        &lcub;
            // The current value of _ao is represented by itself (_ao)
            // or _ao.Current.Value
            Plot("AwesomeOscillator", "ao", _ao);
            // Plot all properties of abands
            Plot("AwesomeOscillator", "slowao", _ao.SlowAo);
            Plot("AwesomeOscillator", "fastao", _ao.FastAo);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class AwesomeOscillatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._ao = self.ao(self._symbol, 10, 20, MovingAverageType.SIMPLE)

    def on_data(self, slice: Slice) -> None:
        if self._ao.is_ready:
            # The current value of self._ao is represented by self._ao.current.value
            self.plot("AwesomeOscillator", "ao", self._ao.current.value)
            # Plot all attributes of self._ao
            self.plot("AwesomeOscillator", "slow_ao", self._ao.slow_ao.current.value)
            self.plot("AwesomeOscillator", "fast_ao", self._ao.fast_ao.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.ao">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>AwesomeOscillator</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class AwesomeOscillatorAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private AwesomeOscillator _awesomeoscillator;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _awesomeoscillator = new AwesomeOscillator(10, 20, MovingAverageType.Simple);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _awesomeoscillator.Update(bar.EndTime, bar.Close);
        if (_awesomeoscillator.IsReady)
        &lcub;
            // The current value of _awesomeoscillator is represented by itself (_awesomeoscillator)
            // or _awesomeoscillator.Current.Value
            Plot("AwesomeOscillator", "awesomeoscillator", _awesomeoscillator);
            // Plot all properties of abands
            Plot("AwesomeOscillator", "slowao", _awesomeoscillator.SlowAo);
            Plot("AwesomeOscillator", "fastao", _awesomeoscillator.FastAo);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class AwesomeOscillatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._awesomeoscillator = AwesomeOscillator(10, 20, MovingAverageType.SIMPLE)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._awesomeoscillator.update(bar.end_time, bar.close)
        if self._awesomeoscillator.is_ready:
            # The current value of self._awesomeoscillator is represented by self._awesomeoscillator.current.value
            self.plot("AwesomeOscillator", "awesomeoscillator", self._awesomeoscillator.current.value)
            # Plot all attributes of self._awesomeoscillator
            self.plot("AwesomeOscillator", "slow_ao", self._awesomeoscillator.slow_ao.current.value)
            self.plot("AwesomeOscillator", "fast_ao", self._awesomeoscillator.fast_ao.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1AwesomeOscillator.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/AwesomeOscillator">reference</a>.</p>