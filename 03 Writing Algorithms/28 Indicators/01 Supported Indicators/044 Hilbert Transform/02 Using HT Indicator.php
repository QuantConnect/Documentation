<p>To create an automatic indicators for <code>HilbertTransform</code>, call the <code class='csharp'>HT</code><code class='python'>ht</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>HT</code><code class='python'>ht</code> method creates a <code>HilbertTransform</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class HilbertTransformAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private HilbertTransform _ht;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _ht = HT(_symbol, 7, 0.635m, 0.338m);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_ht.IsReady)
        &lcub;
            // The current value of _ht is represented by itself (_ht)
            // or _ht.Current.Value
            Plot("HilbertTransform", "ht", _ht);
            // Plot all properties of abands
            Plot("HilbertTransform", "inphase", _ht.InPhase);
            Plot("HilbertTransform", "quadrature", _ht.Quadrature);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class HilbertTransformAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._ht = self.ht(self._symbol, 7, 0.635, 0.338)

    def on_data(self, slice: Slice) -> None:
        if self._ht.is_ready:
            # The current value of self._ht is represented by self._ht.current.value
            self.plot("HilbertTransform", "ht", self._ht.current.value)
            # Plot all attributes of self._ht
            self.plot("HilbertTransform", "in_phase", self._ht.in_phase.current.value)
            self.plot("HilbertTransform", "quadrature", self._ht.quadrature.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.ht">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>HilbertTransform</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class HilbertTransformAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private HilbertTransform _hilberttransform;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _hilberttransform = new HilbertTransform(7, 0.635m, 0.338m);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _hilberttransform.Update(bar.EndTime, bar.Close);
        if (_hilberttransform.IsReady)
        &lcub;
            // The current value of _hilberttransform is represented by itself (_hilberttransform)
            // or _hilberttransform.Current.Value
            Plot("HilbertTransform", "hilberttransform", _hilberttransform);
            // Plot all properties of abands
            Plot("HilbertTransform", "inphase", _hilberttransform.InPhase);
            Plot("HilbertTransform", "quadrature", _hilberttransform.Quadrature);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class HilbertTransformAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._hilberttransform = HilbertTransform(7, 0.635, 0.338)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._hilberttransform.update(bar.end_time, bar.close)
        if self._hilberttransform.is_ready:
            # The current value of self._hilberttransform is represented by self._hilberttransform.current.value
            self.plot("HilbertTransform", "hilberttransform", self._hilberttransform.current.value)
            # Plot all attributes of self._hilberttransform
            self.plot("HilbertTransform", "in_phase", self._hilberttransform.in_phase.current.value)
            self.plot("HilbertTransform", "quadrature", self._hilberttransform.quadrature.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1HilbertTransform.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/HilbertTransform">reference</a>.</p>