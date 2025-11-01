<p>To create an automatic indicators for <code>ArnaudLegouxMovingAverage</code>, call the <code class='csharp'>ALMA</code><code class='python'>alma</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>ALMA</code><code class='python'>alma</code> method creates a <code>ArnaudLegouxMovingAverage</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class ArnaudLegouxMovingAverageAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private ArnaudLegouxMovingAverage _alma;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _alma = ALMA(_symbol, 10, 6, 0.85m);
    }

    public override void OnData(Slice data)
    {

        if (_alma.IsReady)
        {
            // The current value of _alma is represented by itself (_alma)
            // or _alma.Current.Value
            Plot("ArnaudLegouxMovingAverage", "alma", _alma);
        }
    }
}</pre>
<pre class="python">class ArnaudLegouxMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._alma = self.alma(self._symbol, 10, 6, 0.85)

    def on_data(self, slice: Slice) -> None:

        if self._alma.is_ready:
            # The current value of self._alma is represented by self._alma.current.value
            self.plot("ArnaudLegouxMovingAverage", "alma", self._alma.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.alma">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>ArnaudLegouxMovingAverage</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class ArnaudLegouxMovingAverageAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private ArnaudLegouxMovingAverage _arnaudlegouxmovingaverage;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _arnaudlegouxmovingaverage = new ArnaudLegouxMovingAverage(10, 6, 0.85m);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _arnaudlegouxmovingaverage.Update(bar.EndTime, bar.Close);

        if (_arnaudlegouxmovingaverage.IsReady)
        {
            // The current value of _arnaudlegouxmovingaverage is represented by itself (_arnaudlegouxmovingaverage)
            // or _arnaudlegouxmovingaverage.Current.Value
            Plot("ArnaudLegouxMovingAverage", "arnaudlegouxmovingaverage", _arnaudlegouxmovingaverage);
        }
    }
}</pre>
<pre class="python">class ArnaudLegouxMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._arnaudlegouxmovingaverage = ArnaudLegouxMovingAverage(10, 6, 0.85)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._arnaudlegouxmovingaverage.update(bar.end_time, bar.close)

        if self._arnaudlegouxmovingaverage.is_ready:
            # The current value of self._arnaudlegouxmovingaverage is represented by self._arnaudlegouxmovingaverage.current.value
            self.plot("ArnaudLegouxMovingAverage", "arnaudlegouxmovingaverage", self._arnaudlegouxmovingaverage.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1ArnaudLegouxMovingAverage.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/ArnaudLegouxMovingAverage">reference</a>.</p>