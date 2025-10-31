<p>To create an automatic indicators for <code>DetrendedPriceOscillator</code>, call the <code class='csharp'>DPO</code><code class='python'>dpo</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>DPO</code><code class='python'>dpo</code> method creates a <code>DetrendedPriceOscillator</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class DetrendedPriceOscillatorAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private DetrendedPriceOscillator _dpo;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _dpo = DPO(_symbol, 20);
    }

    public override void OnData(Slice data)
    {

        if (_dpo.IsReady)
        {
            // The current value of _dpo is represented by itself (_dpo)
            // or _dpo.Current.Value
            Plot("DetrendedPriceOscillator", "dpo", _dpo);
        }
    }
}</pre>
<pre class="python">class DetrendedPriceOscillatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._dpo = self.dpo(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:

        if self._dpo.is_ready:
            # The current value of self._dpo is represented by self._dpo.current.value
            self.plot("DetrendedPriceOscillator", "dpo", self._dpo.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.dpo">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>DetrendedPriceOscillator</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class DetrendedPriceOscillatorAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private DetrendedPriceOscillator _detrendedpriceoscillator;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _detrendedpriceoscillator = new DetrendedPriceOscillator(20);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _detrendedpriceoscillator.Update(bar.EndTime, bar.Close);

        if (_detrendedpriceoscillator.IsReady)
        {
            // The current value of _detrendedpriceoscillator is represented by itself (_detrendedpriceoscillator)
            // or _detrendedpriceoscillator.Current.Value
            Plot("DetrendedPriceOscillator", "detrendedpriceoscillator", _detrendedpriceoscillator);
        }
    }
}</pre>
<pre class="python">class DetrendedPriceOscillatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._detrendedpriceoscillator = DetrendedPriceOscillator(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._detrendedpriceoscillator.update(bar.end_time, bar.close)

        if self._detrendedpriceoscillator.is_ready:
            # The current value of self._detrendedpriceoscillator is represented by self._detrendedpriceoscillator.current.value
            self.plot("DetrendedPriceOscillator", "detrendedpriceoscillator", self._detrendedpriceoscillator.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1DetrendedPriceOscillator.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/DetrendedPriceOscillator">reference</a>.</p>