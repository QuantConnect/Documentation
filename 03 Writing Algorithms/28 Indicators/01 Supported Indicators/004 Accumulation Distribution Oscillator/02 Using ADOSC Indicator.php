<p>To create an automatic indicators for <code>AccumulationDistributionOscillator</code>, call the <code class='csharp'>ADOSC</code><code class='python'>adosc</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>ADOSC</code><code class='python'>adosc</code> method creates a <code>AccumulationDistributionOscillator</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class AccumulationDistributionOscillatorAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private AccumulationDistributionOscillator _adosc;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _adosc = ADOSC(_symbol, 10, 20);
    }

    public override void OnData(Slice data)
    {

        if (_adosc.IsReady)
        {
            // The current value of _adosc is represented by itself (_adosc)
            // or _adosc.Current.Value
            Plot("AccumulationDistributionOscillator", "adosc", _adosc);
        }
    }
}</pre>
<pre class="python">class AccumulationDistributionOscillatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._adosc = self.adosc(self._symbol, 10, 20)

    def on_data(self, slice: Slice) -> None:

        if self._adosc.is_ready:
            # The current value of self._adosc is represented by self._adosc.current.value
            self.plot("AccumulationDistributionOscillator", "adosc", self._adosc.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.adosc">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>AccumulationDistributionOscillator</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class AccumulationDistributionOscillatorAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private AccumulationDistributionOscillator _accumulationdistributionoscillator;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _accumulationdistributionoscillator = new AccumulationDistributionOscillator(10, 20);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _accumulationdistributionoscillator.Update(bar.EndTime, bar.Close);

        if (_accumulationdistributionoscillator.IsReady)
        {
            // The current value of _accumulationdistributionoscillator is represented by itself (_accumulationdistributionoscillator)
            // or _accumulationdistributionoscillator.Current.Value
            Plot("AccumulationDistributionOscillator", "accumulationdistributionoscillator", _accumulationdistributionoscillator);
        }
    }
}</pre>
<pre class="python">class AccumulationDistributionOscillatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._accumulationdistributionoscillator = AccumulationDistributionOscillator(10, 20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._accumulationdistributionoscillator.update(bar.end_time, bar.close)

        if self._accumulationdistributionoscillator.is_ready:
            # The current value of self._accumulationdistributionoscillator is represented by self._accumulationdistributionoscillator.current.value
            self.plot("AccumulationDistributionOscillator", "accumulationdistributionoscillator", self._accumulationdistributionoscillator.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1AccumulationDistributionOscillator.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/AccumulationDistributionOscillator">reference</a>.</p>