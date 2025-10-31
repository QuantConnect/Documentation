<p>To create an automatic indicators for <code>ExponentialMovingAverage</code>, call the <code class='csharp'>EMA</code><code class='python'>ema</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>EMA</code><code class='python'>ema</code> method creates a <code>ExponentialMovingAverage</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class ExponentialMovingAverageAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private ExponentialMovingAverage _ema;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _ema = EMA(_symbol, 20, 0.5m);
    }

    public override void OnData(Slice data)
    {

        if (_ema.IsReady)
        {
            // The current value of _ema is represented by itself (_ema)
            // or _ema.Current.Value
            Plot("ExponentialMovingAverage", "ema", _ema);
        }
    }
}</pre>
<pre class="python">class ExponentialMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._ema = self.ema(self._symbol, 20, 0.5)

    def on_data(self, slice: Slice) -> None:

        if self._ema.is_ready:
            # The current value of self._ema is represented by self._ema.current.value
            self.plot("ExponentialMovingAverage", "ema", self._ema.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.ema">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>ExponentialMovingAverage</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class ExponentialMovingAverageAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private ExponentialMovingAverage _exponentialmovingaverage;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _exponentialmovingaverage = new ExponentialMovingAverage(20, 0.5m);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _exponentialmovingaverage.Update(bar.EndTime, bar.Close);

        if (_exponentialmovingaverage.IsReady)
        {
            // The current value of _exponentialmovingaverage is represented by itself (_exponentialmovingaverage)
            // or _exponentialmovingaverage.Current.Value
            Plot("ExponentialMovingAverage", "exponentialmovingaverage", _exponentialmovingaverage);
        }
    }
}</pre>
<pre class="python">class ExponentialMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._exponentialmovingaverage = ExponentialMovingAverage(20, 0.5)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._exponentialmovingaverage.update(bar.end_time, bar.close)

        if self._exponentialmovingaverage.is_ready:
            # The current value of self._exponentialmovingaverage is represented by self._exponentialmovingaverage.current.value
            self.plot("ExponentialMovingAverage", "exponentialmovingaverage", self._exponentialmovingaverage.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1ExponentialMovingAverage.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/ExponentialMovingAverage">reference</a>.</p>