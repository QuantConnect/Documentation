<p>To create an automatic indicators for <code>DeMarkerIndicator</code>, call the <code class='csharp'>DEM</code><code class='python'>dem</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>DEM</code><code class='python'>dem</code> method creates a <code>DeMarkerIndicator</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class DeMarkerIndicatorAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private DeMarkerIndicator _dem;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _dem = DEM(_symbol, 20, MovingAverageType.Simple);
    }

    public override void OnData(Slice data)
    {

        if (_dem.IsReady)
        {
            // The current value of _dem is represented by itself (_dem)
            // or _dem.Current.Value
            Plot("DeMarkerIndicator", "dem", _dem);
        }
    }
}</pre>
<pre class="python">class DeMarkerIndicatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._dem = self.dem(self._symbol, 20, MovingAverageType.SIMPLE)

    def on_data(self, slice: Slice) -> None:

        if self._dem.is_ready:
            # The current value of self._dem is represented by self._dem.current.value
            self.plot("DeMarkerIndicator", "dem", self._dem.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.dem">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>DeMarkerIndicator</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class DeMarkerIndicatorAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private DeMarkerIndicator _demarkerindicator;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _demarkerindicator = new DeMarkerIndicator(20, MovingAverageType.Simple);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _demarkerindicator.Update(bar.EndTime, bar.Close);

        if (_demarkerindicator.IsReady)
        {
            // The current value of _demarkerindicator is represented by itself (_demarkerindicator)
            // or _demarkerindicator.Current.Value
            Plot("DeMarkerIndicator", "demarkerindicator", _demarkerindicator);
        }
    }
}</pre>
<pre class="python">class DeMarkerIndicatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._demarkerindicator = DeMarkerIndicator(20, MovingAverageType.SIMPLE)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._demarkerindicator.update(bar.end_time, bar.close)

        if self._demarkerindicator.is_ready:
            # The current value of self._demarkerindicator is represented by self._demarkerindicator.current.value
            self.plot("DeMarkerIndicator", "demarkerindicator", self._demarkerindicator.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1DeMarkerIndicator.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/DeMarkerIndicator">reference</a>.</p>