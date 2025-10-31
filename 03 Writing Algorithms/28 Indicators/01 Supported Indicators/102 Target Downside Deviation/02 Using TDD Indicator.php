<p>To create an automatic indicators for <code>TargetDownsideDeviation</code>, call the <code class='csharp'>TDD</code><code class='python'>tdd</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>TDD</code><code class='python'>tdd</code> method creates a <code>TargetDownsideDeviation</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class TargetDownsideDeviationAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private TargetDownsideDeviation _tdd;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _tdd = TDD(_symbol, 50);
    }

    public override void OnData(Slice data)
    {

        if (_tdd.IsReady)
        {
            // The current value of _tdd is represented by itself (_tdd)
            // or _tdd.Current.Value
            Plot("TargetDownsideDeviation", "tdd", _tdd);
        }
    }
}</pre>
<pre class="python">class TargetDownsideDeviationAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._tdd = self.tdd(self._symbol, 50)

    def on_data(self, slice: Slice) -> None:

        if self._tdd.is_ready:
            # The current value of self._tdd is represented by self._tdd.current.value
            self.plot("TargetDownsideDeviation", "tdd", self._tdd.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.tdd">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>TargetDownsideDeviation</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class TargetDownsideDeviationAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private TargetDownsideDeviation _indicatorextensions.of;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _indicatorextensions.of = new IndicatorExtensions.Of(new TargetDownsideDeviation(50), new RateOfChange(1));
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _indicatorextensions.of.Update(bar.EndTime, bar.Close);

        if (_indicatorextensions.of.IsReady)
        {
            // The current value of _indicatorextensions.of is represented by itself (_indicatorextensions.of)
            // or _indicatorextensions.of.Current.Value
            Plot("TargetDownsideDeviation", "indicatorextensions.of", _indicatorextensions.of);
        }
    }
}</pre>
<pre class="python">class TargetDownsideDeviationAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._indicatorextensions.of = IndicatorExtensions.of(TargetDownsideDeviation(50), RateOfChange(1))

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._indicatorextensions.of.update(bar.end_time, bar.close)

        if self._indicatorextensions.of.is_ready:
            # The current value of self._indicatorextensions.of is represented by self._indicatorextensions.of.current.value
            self.plot("TargetDownsideDeviation", "indicatorextensions.of", self._indicatorextensions.of.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1TargetDownsideDeviation.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/TargetDownsideDeviation">reference</a>.</p>