<p>To create an automatic indicators for <code>FractalAdaptiveMovingAverage</code>, call the <code class='csharp'>FRAMA</code><code class='python'>frama</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>FRAMA</code><code class='python'>frama</code> method creates a <code>FractalAdaptiveMovingAverage</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class FractalAdaptiveMovingAverageAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private FractalAdaptiveMovingAverage _frama;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _frama = FRAMA(_symbol, 20, 198);
    }

    public override void OnData(Slice data)
    {

        if (_frama.IsReady)
        {
            // The current value of _frama is represented by itself (_frama)
            // or _frama.Current.Value
            Plot("FractalAdaptiveMovingAverage", "frama", _frama);
        }
    }
}</pre>
<pre class="python">class FractalAdaptiveMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._frama = self.frama(self._symbol, 20, 198)

    def on_data(self, slice: Slice) -> None:

        if self._frama.is_ready:
            # The current value of self._frama is represented by self._frama.current.value
            self.plot("FractalAdaptiveMovingAverage", "frama", self._frama.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.frama">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>FractalAdaptiveMovingAverage</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class FractalAdaptiveMovingAverageAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private FractalAdaptiveMovingAverage _fractaladaptivemovingaverage;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _fractaladaptivemovingaverage = new FractalAdaptiveMovingAverage(20, 198);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _fractaladaptivemovingaverage.Update(bar.EndTime, bar.Close);

        if (_fractaladaptivemovingaverage.IsReady)
        {
            // The current value of _fractaladaptivemovingaverage is represented by itself (_fractaladaptivemovingaverage)
            // or _fractaladaptivemovingaverage.Current.Value
            Plot("FractalAdaptiveMovingAverage", "fractaladaptivemovingaverage", _fractaladaptivemovingaverage);
        }
    }
}</pre>
<pre class="python">class FractalAdaptiveMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._fractaladaptivemovingaverage = FractalAdaptiveMovingAverage(20, 198)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._fractaladaptivemovingaverage.update(bar.end_time, bar.close)

        if self._fractaladaptivemovingaverage.is_ready:
            # The current value of self._fractaladaptivemovingaverage is represented by self._fractaladaptivemovingaverage.current.value
            self.plot("FractalAdaptiveMovingAverage", "fractaladaptivemovingaverage", self._fractaladaptivemovingaverage.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1FractalAdaptiveMovingAverage.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/FractalAdaptiveMovingAverage">reference</a>.</p>