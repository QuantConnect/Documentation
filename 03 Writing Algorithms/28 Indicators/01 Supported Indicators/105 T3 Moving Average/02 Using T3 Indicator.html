<p>To create an automatic indicators for <code>T3MovingAverage</code>, call the <code class='csharp'>T3</code><code class='python'>t_3</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>T3</code><code class='python'>t_3</code> method creates a <code>T3MovingAverage</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class T3MovingAverageAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private T3MovingAverage _t3;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _t3 = T3(_symbol, 30, 0.7m);
    }

    public override void OnData(Slice data)
    {

        if (_t3.IsReady)
        {
            // The current value of _t3 is represented by itself (_t3)
            // or _t3.Current.Value
            Plot("T3MovingAverage", "t3", _t3);
        }
    }
}</pre>
<pre class="python">class T3MovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._t_3 = self.t_3(self._symbol, 30, 0.7)

    def on_data(self, slice: Slice) -> None:

        if self._t_3.is_ready:
            # The current value of self._t_3 is represented by self._t_3.current.value
            self.plot("T3MovingAverage", "t_3", self._t_3.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.t_3">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>T3MovingAverage</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class T3MovingAverageAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private T3MovingAverage _t3movingaverage;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _t3movingaverage = new T3MovingAverage(30, 0.7m);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _t3movingaverage.Update(bar.EndTime, bar.Close);

        if (_t3movingaverage.IsReady)
        {
            // The current value of _t3movingaverage is represented by itself (_t3movingaverage)
            // or _t3movingaverage.Current.Value
            Plot("T3MovingAverage", "t3movingaverage", _t3movingaverage);
        }
    }
}</pre>
<pre class="python">class T3MovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._t3movingaverage = T3MovingAverage(30, 0.7)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._t3movingaverage.update(bar.end_time, bar.close)

        if self._t3movingaverage.is_ready:
            # The current value of self._t3movingaverage is represented by self._t3movingaverage.current.value
            self.plot("T3MovingAverage", "t3movingaverage", self._t3movingaverage.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1T3MovingAverage.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/T3MovingAverage">reference</a>.</p>