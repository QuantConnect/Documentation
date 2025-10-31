<p>To create an automatic indicators for <code>UltimateOscillator</code>, call the <code class='csharp'>ULTOSC</code><code class='python'>ultosc</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>ULTOSC</code><code class='python'>ultosc</code> method creates a <code>UltimateOscillator</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class UltimateOscillatorAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private UltimateOscillator _ultosc;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _ultosc = ULTOSC(_symbol, 5, 10, 20);
    }

    public override void OnData(Slice data)
    {

        if (_ultosc.IsReady)
        {
            // The current value of _ultosc is represented by itself (_ultosc)
            // or _ultosc.Current.Value
            Plot("UltimateOscillator", "ultosc", _ultosc);
        }
    }
}</pre>
<pre class="python">class UltimateOscillatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._ultosc = self.ultosc(self._symbol, 5, 10, 20)

    def on_data(self, slice: Slice) -> None:

        if self._ultosc.is_ready:
            # The current value of self._ultosc is represented by self._ultosc.current.value
            self.plot("UltimateOscillator", "ultosc", self._ultosc.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.ultosc">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>UltimateOscillator</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class UltimateOscillatorAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private UltimateOscillator _ultimateoscillator;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _ultimateoscillator = new UltimateOscillator(5, 10, 20);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _ultimateoscillator.Update(bar.EndTime, bar.Close);

        if (_ultimateoscillator.IsReady)
        {
            // The current value of _ultimateoscillator is represented by itself (_ultimateoscillator)
            // or _ultimateoscillator.Current.Value
            Plot("UltimateOscillator", "ultimateoscillator", _ultimateoscillator);
        }
    }
}</pre>
<pre class="python">class UltimateOscillatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._ultimateoscillator = UltimateOscillator(5, 10, 20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._ultimateoscillator.update(bar.end_time, bar.close)

        if self._ultimateoscillator.is_ready:
            # The current value of self._ultimateoscillator is represented by self._ultimateoscillator.current.value
            self.plot("UltimateOscillator", "ultimateoscillator", self._ultimateoscillator.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1UltimateOscillator.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/UltimateOscillator">reference</a>.</p>