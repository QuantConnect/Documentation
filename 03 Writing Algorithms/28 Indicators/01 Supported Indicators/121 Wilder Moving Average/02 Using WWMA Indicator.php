<p>To create an automatic indicators for <code>WilderMovingAverage</code>, call the <code class='csharp'>WWMA</code><code class='python'>wwma</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>WWMA</code><code class='python'>wwma</code> method creates a <code>WilderMovingAverage</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class WilderMovingAverageAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private WilderMovingAverage _wwma;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _wwma = WWMA(_symbol, 20);
    }

    public override void OnData(Slice data)
    {

        if (_wwma.IsReady)
        {
            // The current value of _wwma is represented by itself (_wwma)
            // or _wwma.Current.Value
            Plot("WilderMovingAverage", "wwma", _wwma);
        }
    }
}</pre>
<pre class="python">class WilderMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._wwma = self.wwma(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:

        if self._wwma.is_ready:
            # The current value of self._wwma is represented by self._wwma.current.value
            self.plot("WilderMovingAverage", "wwma", self._wwma.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.wwma">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>WilderMovingAverage</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class WilderMovingAverageAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private WilderMovingAverage _wildermovingaverage;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _wildermovingaverage = new WilderMovingAverage(20);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _wildermovingaverage.Update(bar.EndTime, bar.Close);

        if (_wildermovingaverage.IsReady)
        {
            // The current value of _wildermovingaverage is represented by itself (_wildermovingaverage)
            // or _wildermovingaverage.Current.Value
            Plot("WilderMovingAverage", "wildermovingaverage", _wildermovingaverage);
        }
    }
}</pre>
<pre class="python">class WilderMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._wildermovingaverage = WilderMovingAverage(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._wildermovingaverage.update(bar.end_time, bar.close)

        if self._wildermovingaverage.is_ready:
            # The current value of self._wildermovingaverage is represented by self._wildermovingaverage.current.value
            self.plot("WilderMovingAverage", "wildermovingaverage", self._wildermovingaverage.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1WilderMovingAverage.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/WilderMovingAverage">reference</a>.</p>