<p>To create an automatic indicators for <code>TimeSeriesForecast</code>, call the <code class='csharp'>TSF</code><code class='python'>tsf</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>TSF</code><code class='python'>tsf</code> method creates a <code>TimeSeriesForecast</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class TimeSeriesForecastAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private TimeSeriesForecast _tsf;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _tsf = TSF(_symbol, 3);
    }

    public override void OnData(Slice data)
    {

        if (_tsf.IsReady)
        {
            // The current value of _tsf is represented by itself (_tsf)
            // or _tsf.Current.Value
            Plot("TimeSeriesForecast", "tsf", _tsf);
        }
    }
}</pre>
<pre class="python">class TimeSeriesForecastAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._tsf = self.tsf(self._symbol, 3)

    def on_data(self, slice: Slice) -> None:

        if self._tsf.is_ready:
            # The current value of self._tsf is represented by self._tsf.current.value
            self.plot("TimeSeriesForecast", "tsf", self._tsf.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.tsf">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>TimeSeriesForecast</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class TimeSeriesForecastAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private TimeSeriesForecast _timeseriesforecast;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _timeseriesforecast = new TimeSeriesForecast(3);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _timeseriesforecast.Update(bar.EndTime, bar.Close);

        if (_timeseriesforecast.IsReady)
        {
            // The current value of _timeseriesforecast is represented by itself (_timeseriesforecast)
            // or _timeseriesforecast.Current.Value
            Plot("TimeSeriesForecast", "timeseriesforecast", _timeseriesforecast);
        }
    }
}</pre>
<pre class="python">class TimeSeriesForecastAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._timeseriesforecast = TimeSeriesForecast(3)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._timeseriesforecast.update(bar.end_time, bar.close)

        if self._timeseriesforecast.is_ready:
            # The current value of self._timeseriesforecast is represented by self._timeseriesforecast.current.value
            self.plot("TimeSeriesForecast", "timeseriesforecast", self._timeseriesforecast.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1TimeSeriesForecast.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/TimeSeriesForecast">reference</a>.</p>