<p>To create an automatic indicators for <code>HullMovingAverage</code>, call the <code class='csharp'>HMA</code><code class='python'>hma</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>HMA</code><code class='python'>hma</code> method creates a <code>HullMovingAverage</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class HullMovingAverageAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private HullMovingAverage _hma;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _hma = HMA(_symbol, 20);
    }

    public override void OnData(Slice data)
    {

        if (_hma.IsReady)
        {
            // The current value of _hma is represented by itself (_hma)
            // or _hma.Current.Value
            Plot("HullMovingAverage", "hma", _hma);
        }
    }
}</pre>
<pre class="python">class HullMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._hma = self.hma(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:

        if self._hma.is_ready:
            # The current value of self._hma is represented by self._hma.current.value
            self.plot("HullMovingAverage", "hma", self._hma.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.hma">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>HullMovingAverage</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class HullMovingAverageAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private HullMovingAverage _hullmovingaverage;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _hullmovingaverage = new HullMovingAverage(20);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _hullmovingaverage.Update(bar.EndTime, bar.Close);

        if (_hullmovingaverage.IsReady)
        {
            // The current value of _hullmovingaverage is represented by itself (_hullmovingaverage)
            // or _hullmovingaverage.Current.Value
            Plot("HullMovingAverage", "hullmovingaverage", _hullmovingaverage);
        }
    }
}</pre>
<pre class="python">class HullMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._hullmovingaverage = HullMovingAverage(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._hullmovingaverage.update(bar.end_time, bar.close)

        if self._hullmovingaverage.is_ready:
            # The current value of self._hullmovingaverage is represented by self._hullmovingaverage.current.value
            self.plot("HullMovingAverage", "hullmovingaverage", self._hullmovingaverage.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1HullMovingAverage.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/HullMovingAverage">reference</a>.</p>