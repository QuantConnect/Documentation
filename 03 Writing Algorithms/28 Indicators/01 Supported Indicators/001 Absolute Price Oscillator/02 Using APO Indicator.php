<p>To create an automatic indicators for <code>AbsolutePriceOscillator</code>, call the <code class='csharp'>APO</code><code class='python'>apo</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>APO</code><code class='python'>apo</code> method creates a <code>AbsolutePriceOscillator</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class AbsolutePriceOscillatorAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private AbsolutePriceOscillator _apo;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _apo = APO(_symbol, 10, 20, MovingAverageType.Simple);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_apo.IsReady)
        &lcub;
            // The current value of _apo is represented by itself (_apo)
            // or _apo.Current.Value
            Plot("AbsolutePriceOscillator", "apo", _apo);
            // Plot all properties of abands
            Plot("AbsolutePriceOscillator", "fast", _apo.Fast);
            Plot("AbsolutePriceOscillator", "slow", _apo.Slow);
            Plot("AbsolutePriceOscillator", "signal", _apo.Signal);
            Plot("AbsolutePriceOscillator", "histogram", _apo.Histogram);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class AbsolutePriceOscillatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._apo = self.apo(self._symbol, 10, 20, MovingAverageType.SIMPLE)

    def on_data(self, slice: Slice) -> None:
        if self._apo.is_ready:
            # The current value of self._apo is represented by self._apo.current.value
            self.plot("AbsolutePriceOscillator", "apo", self._apo.current.value)
            # Plot all attributes of self._apo
            self.plot("AbsolutePriceOscillator", "fast", self._apo.fast.current.value)
            self.plot("AbsolutePriceOscillator", "slow", self._apo.slow.current.value)
            self.plot("AbsolutePriceOscillator", "signal", self._apo.signal.current.value)
            self.plot("AbsolutePriceOscillator", "histogram", self._apo.histogram.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.apo">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>AbsolutePriceOscillator</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class AbsolutePriceOscillatorAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private AbsolutePriceOscillator _absolutepriceoscillator;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _absolutepriceoscillator = new AbsolutePriceOscillator(10, 20, MovingAverageType.Simple);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _absolutepriceoscillator.Update(bar.EndTime, bar.Close);
        if (_absolutepriceoscillator.IsReady)
        &lcub;
            // The current value of _absolutepriceoscillator is represented by itself (_absolutepriceoscillator)
            // or _absolutepriceoscillator.Current.Value
            Plot("AbsolutePriceOscillator", "absolutepriceoscillator", _absolutepriceoscillator);
            // Plot all properties of abands
            Plot("AbsolutePriceOscillator", "fast", _absolutepriceoscillator.Fast);
            Plot("AbsolutePriceOscillator", "slow", _absolutepriceoscillator.Slow);
            Plot("AbsolutePriceOscillator", "signal", _absolutepriceoscillator.Signal);
            Plot("AbsolutePriceOscillator", "histogram", _absolutepriceoscillator.Histogram);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class AbsolutePriceOscillatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._absolutepriceoscillator = AbsolutePriceOscillator(10, 20, MovingAverageType.SIMPLE)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._absolutepriceoscillator.update(bar.end_time, bar.close)
        if self._absolutepriceoscillator.is_ready:
            # The current value of self._absolutepriceoscillator is represented by self._absolutepriceoscillator.current.value
            self.plot("AbsolutePriceOscillator", "absolutepriceoscillator", self._absolutepriceoscillator.current.value)
            # Plot all attributes of self._absolutepriceoscillator
            self.plot("AbsolutePriceOscillator", "fast", self._absolutepriceoscillator.fast.current.value)
            self.plot("AbsolutePriceOscillator", "slow", self._absolutepriceoscillator.slow.current.value)
            self.plot("AbsolutePriceOscillator", "signal", self._absolutepriceoscillator.signal.current.value)
            self.plot("AbsolutePriceOscillator", "histogram", self._absolutepriceoscillator.histogram.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1AbsolutePriceOscillator.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/AbsolutePriceOscillator">reference</a>.</p>