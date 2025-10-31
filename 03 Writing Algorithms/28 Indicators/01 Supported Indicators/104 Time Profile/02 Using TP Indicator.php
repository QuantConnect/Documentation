<p>To create an automatic indicators for <code>TimeProfile</code>, call the <code class='csharp'>TP</code><code class='python'>tp</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>TP</code><code class='python'>tp</code> method creates a <code>TimeProfile</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class TimeProfileAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private TimeProfile _tp;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _tp = TP(_symbol, 3, 0.70m, 0.05m);
    }

    public override void OnData(Slice data)
    {

        if (_tp.IsReady)
        {
            // The current value of _tp is represented by itself (_tp)
            // or _tp.Current.Value
            Plot("TimeProfile", "tp", _tp);
        }
    }
}</pre>
<pre class="python">class TimeProfileAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._tp = self.tp(self._symbol, 3, 0.70, 0.05)

    def on_data(self, slice: Slice) -> None:

        if self._tp.is_ready:
            # The current value of self._tp is represented by self._tp.current.value
            self.plot("TimeProfile", "tp", self._tp.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.tp">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>TimeProfile</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class TimeProfileAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private TimeProfile _timeprofile;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _timeprofile = new TimeProfile("TP", 3, 0.70m, 0.05m);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _timeprofile.Update(bar.EndTime, bar.Close);

        if (_timeprofile.IsReady)
        {
            // The current value of _timeprofile is represented by itself (_timeprofile)
            // or _timeprofile.Current.Value
            Plot("TimeProfile", "timeprofile", _timeprofile);
        }
    }
}</pre>
<pre class="python">class TimeProfileAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._timeprofile = TimeProfile("TP", 3, 0.70, 0.05)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._timeprofile.update(bar.end_time, bar.close)

        if self._timeprofile.is_ready:
            # The current value of self._timeprofile is represented by self._timeprofile.current.value
            self.plot("TimeProfile", "timeprofile", self._timeprofile.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1TimeProfile.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/TimeProfile">reference</a>.</p>