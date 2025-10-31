<p>To create an automatic indicators for <code>LogReturn</code>, call the <code class='csharp'>LOGR</code><code class='python'>logr</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>LOGR</code><code class='python'>logr</code> method creates a <code>LogReturn</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class LogReturnAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private LogReturn _logr;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _logr = LOGR(_symbol, 20);
    }

    public override void OnData(Slice data)
    {

        if (_logr.IsReady)
        {
            // The current value of _logr is represented by itself (_logr)
            // or _logr.Current.Value
            Plot("LogReturn", "logr", _logr);
        }
    }
}</pre>
<pre class="python">class LogReturnAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._logr = self.logr(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:

        if self._logr.is_ready:
            # The current value of self._logr is represented by self._logr.current.value
            self.plot("LogReturn", "logr", self._logr.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.logr">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>LogReturn</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class LogReturnAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private LogReturn _logreturn;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _logreturn = new LogReturn(20);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _logreturn.Update(bar.EndTime, bar.Close);

        if (_logreturn.IsReady)
        {
            // The current value of _logreturn is represented by itself (_logreturn)
            // or _logreturn.Current.Value
            Plot("LogReturn", "logreturn", _logreturn);
        }
    }
}</pre>
<pre class="python">class LogReturnAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._logreturn = LogReturn(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._logreturn.update(bar.end_time, bar.close)

        if self._logreturn.is_ready:
            # The current value of self._logreturn is represented by self._logreturn.current.value
            self.plot("LogReturn", "logreturn", self._logreturn.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1LogReturn.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/LogReturn">reference</a>.</p>