<p>To create an automatic indicators for <code>ZeroLagExponentialMovingAverage</code>, call the <code class='csharp'>ZLEMA</code><code class='python'>zlema</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>ZLEMA</code><code class='python'>zlema</code> method creates a <code>ZeroLagExponentialMovingAverage</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class ZeroLagExponentialMovingAverageAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private ZeroLagExponentialMovingAverage _zlema;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _zlema = ZLEMA(_symbol, 10);
    }

    public override void OnData(Slice data)
    {

        if (_zlema.IsReady)
        {
            // The current value of _zlema is represented by itself (_zlema)
            // or _zlema.Current.Value
            Plot("ZeroLagExponentialMovingAverage", "zlema", _zlema);
        }
    }
}</pre>
<pre class="python">class ZeroLagExponentialMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._zlema = self.zlema(self._symbol, 10)

    def on_data(self, slice: Slice) -> None:

        if self._zlema.is_ready:
            # The current value of self._zlema is represented by self._zlema.current.value
            self.plot("ZeroLagExponentialMovingAverage", "zlema", self._zlema.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.zlema">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>ZeroLagExponentialMovingAverage</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class ZeroLagExponentialMovingAverageAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private ZeroLagExponentialMovingAverage _zerolagexponentialmovingaverage;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _zerolagexponentialmovingaverage = new ZeroLagExponentialMovingAverage(10);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _zerolagexponentialmovingaverage.Update(bar.EndTime, bar.Close);

        if (_zerolagexponentialmovingaverage.IsReady)
        {
            // The current value of _zerolagexponentialmovingaverage is represented by itself (_zerolagexponentialmovingaverage)
            // or _zerolagexponentialmovingaverage.Current.Value
            Plot("ZeroLagExponentialMovingAverage", "zerolagexponentialmovingaverage", _zerolagexponentialmovingaverage);
        }
    }
}</pre>
<pre class="python">class ZeroLagExponentialMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._zerolagexponentialmovingaverage = ZeroLagExponentialMovingAverage(10)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._zerolagexponentialmovingaverage.update(bar.end_time, bar.close)

        if self._zerolagexponentialmovingaverage.is_ready:
            # The current value of self._zerolagexponentialmovingaverage is represented by self._zerolagexponentialmovingaverage.current.value
            self.plot("ZeroLagExponentialMovingAverage", "zerolagexponentialmovingaverage", self._zerolagexponentialmovingaverage.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1ZeroLagExponentialMovingAverage.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/ZeroLagExponentialMovingAverage">reference</a>.</p>