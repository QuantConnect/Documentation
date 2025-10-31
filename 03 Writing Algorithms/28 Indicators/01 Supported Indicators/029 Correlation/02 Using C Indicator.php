<p>To create an automatic indicators for <code>Correlation</code>, call the <code class='csharp'>C</code><code class='python'>c</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>C</code><code class='python'>c</code> method creates a <code>Correlation</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class CorrelationAlgorithm : QCAlgorithm
{
    private Symbol _symbol,_reference;
    private Correlation _c;

    public override void Initialize()
    {
        _symbol = AddEquity("QQQ", Resolution.Daily).Symbol;
        _reference = AddEquity("SPY", Resolution.Daily).Symbol;
        _c = C(_symbol, _reference, 20, CorrelationType.Pearson);
    }

    public override void OnData(Slice data)
    {

        if (_c.IsReady)
        {
            // The current value of _c is represented by itself (_c)
            // or _c.Current.Value
            Plot("Correlation", "c", _c);
        }
    }
}</pre>
<pre class="python">class CorrelationAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("QQQ", Resolution.DAILY).symbol
        self._reference = self.add_equity("SPY", Resolution.DAILY).symbol
        self._c = self.c(self._symbol, self._reference, 20, CorrelationType.PEARSON)

    def on_data(self, slice: Slice) -> None:

        if self._c.is_ready:
            # The current value of self._c is represented by self._c.current.value
            self.plot("Correlation", "c", self._c.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.c">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>Correlation</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class CorrelationAlgorithm : QCAlgorithm
{
    private Symbol _symbol,_reference;
    private Correlation _correlation;

    public override void Initialize()
    {
        _symbol = AddEquity("QQQ", Resolution.Daily).Symbol;
        _reference = AddEquity("SPY", Resolution.Daily).Symbol;
        _correlation = new Correlation("", _symbol, _reference, 20, CorrelationType.Pearson);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _correlation.Update(bar.EndTime, bar.Close);
        if (data.Bars.TryGetValue(_reference, out var bar))
            _correlation.Update(bar.EndTime, bar.Close);

        if (_correlation.IsReady)
        {
            // The current value of _correlation is represented by itself (_correlation)
            // or _correlation.Current.Value
            Plot("Correlation", "correlation", _correlation);
        }
    }
}</pre>
<pre class="python">class CorrelationAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("QQQ", Resolution.DAILY).symbol
        self._reference = self.add_equity("SPY", Resolution.DAILY).symbol
        self._correlation = Correlation("", self._symbol, self._reference, 20, CorrelationType.PEARSON)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._correlation.update(bar.end_time, bar.close)
        bar = slice.bars.get(self._reference)
        if bar:
            self._correlation.update(bar.end_time, bar.close)

        if self._correlation.is_ready:
            # The current value of self._correlation is represented by self._correlation.current.value
            self.plot("Correlation", "correlation", self._correlation.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1Correlation.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/Correlation">reference</a>.</p>