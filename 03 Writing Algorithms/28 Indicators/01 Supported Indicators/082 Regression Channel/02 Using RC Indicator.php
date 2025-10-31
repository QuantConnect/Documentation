<p>To create an automatic indicators for <code>RegressionChannel</code>, call the <code class='csharp'>RC</code><code class='python'>rc</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>RC</code><code class='python'>rc</code> method creates a <code>RegressionChannel</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class RegressionChannelAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private RegressionChannel _rc;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _rc = RC(_symbol, 20, 2);
    }

    public override void OnData(Slice data)
    {

        if (_rc.IsReady)
        {
            // The current value of _rc is represented by itself (_rc)
            // or _rc.Current.Value
            Plot("RegressionChannel", "rc", _rc);
            // Plot all properties of abands
            Plot("RegressionChannel", "linearregression", _rc.LinearRegression);
            Plot("RegressionChannel", "upperchannel", _rc.UpperChannel);
            Plot("RegressionChannel", "lowerchannel", _rc.LowerChannel);
            Plot("RegressionChannel", "intercept", _rc.Intercept);
            Plot("RegressionChannel", "slope", _rc.Slope);
        }
    }
}</pre>
<pre class="python">class RegressionChannelAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._rc = self.rc(self._symbol, 20, 2)

    def on_data(self, slice: Slice) -> None:

        if self._rc.is_ready:
            # The current value of self._rc is represented by self._rc.current.value
            self.plot("RegressionChannel", "rc", self._rc.current.value)
            # Plot all attributes of self._rc
            self.plot("RegressionChannel", "linear_regression", self._rc.linear_regression.current.value)
            self.plot("RegressionChannel", "upper_channel", self._rc.upper_channel.current.value)
            self.plot("RegressionChannel", "lower_channel", self._rc.lower_channel.current.value)
            self.plot("RegressionChannel", "intercept", self._rc.intercept.current.value)
            self.plot("RegressionChannel", "slope", self._rc.slope.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.rc">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>RegressionChannel</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class RegressionChannelAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private RegressionChannel _regressionchannel;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _regressionchannel = new RegressionChannel(20, 2);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _regressionchannel.Update(bar.EndTime, bar.Close);

        if (_regressionchannel.IsReady)
        {
            // The current value of _regressionchannel is represented by itself (_regressionchannel)
            // or _regressionchannel.Current.Value
            Plot("RegressionChannel", "regressionchannel", _regressionchannel);
            // Plot all properties of abands
            Plot("RegressionChannel", "linearregression", _regressionchannel.LinearRegression);
            Plot("RegressionChannel", "upperchannel", _regressionchannel.UpperChannel);
            Plot("RegressionChannel", "lowerchannel", _regressionchannel.LowerChannel);
            Plot("RegressionChannel", "intercept", _regressionchannel.Intercept);
            Plot("RegressionChannel", "slope", _regressionchannel.Slope);
        }
    }
}</pre>
<pre class="python">class RegressionChannelAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._regressionchannel = RegressionChannel(20, 2)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._regressionchannel.update(bar.end_time, bar.close)

        if self._regressionchannel.is_ready:
            # The current value of self._regressionchannel is represented by self._regressionchannel.current.value
            self.plot("RegressionChannel", "regressionchannel", self._regressionchannel.current.value)
            # Plot all attributes of self._regressionchannel
            self.plot("RegressionChannel", "linear_regression", self._regressionchannel.linear_regression.current.value)
            self.plot("RegressionChannel", "upper_channel", self._regressionchannel.upper_channel.current.value)
            self.plot("RegressionChannel", "lower_channel", self._regressionchannel.lower_channel.current.value)
            self.plot("RegressionChannel", "intercept", self._regressionchannel.intercept.current.value)
            self.plot("RegressionChannel", "slope", self._regressionchannel.slope.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1RegressionChannel.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/RegressionChannel">reference</a>.</p>