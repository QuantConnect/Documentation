<p>To create an automatic indicators for <code>RateOfChange</code>, call the <code class='csharp'>ROC</code><code class='python'>roc</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>ROC</code><code class='python'>roc</code> method creates a <code>RateOfChange</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class RateOfChangeAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private RateOfChange _roc;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _roc = ROC(_symbol, 10);
    }

    public override void OnData(Slice data)
    {

        if (_roc.IsReady)
        {
            // The current value of _roc is represented by itself (_roc)
            // or _roc.Current.Value
            Plot("RateOfChange", "roc", _roc);
        }
    }
}</pre>
<pre class="python">class RateOfChangeAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._roc = self.roc(self._symbol, 10)

    def on_data(self, slice: Slice) -> None:

        if self._roc.is_ready:
            # The current value of self._roc is represented by self._roc.current.value
            self.plot("RateOfChange", "roc", self._roc.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.roc">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>RateOfChange</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class RateOfChangeAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private RateOfChange _rateofchange;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _rateofchange = new RateOfChange(10);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _rateofchange.Update(bar.EndTime, bar.Close);

        if (_rateofchange.IsReady)
        {
            // The current value of _rateofchange is represented by itself (_rateofchange)
            // or _rateofchange.Current.Value
            Plot("RateOfChange", "rateofchange", _rateofchange);
        }
    }
}</pre>
<pre class="python">class RateOfChangeAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._rateofchange = RateOfChange(10)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._rateofchange.update(bar.end_time, bar.close)

        if self._rateofchange.is_ready:
            # The current value of self._rateofchange is represented by self._rateofchange.current.value
            self.plot("RateOfChange", "rateofchange", self._rateofchange.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1RateOfChange.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/RateOfChange">reference</a>.</p>