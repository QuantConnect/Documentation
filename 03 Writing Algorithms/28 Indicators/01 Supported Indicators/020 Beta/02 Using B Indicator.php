<p>To create an automatic indicators for <code>Beta</code>, call the <code class='csharp'>B</code><code class='python'>b</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>B</code><code class='python'>b</code> method creates a <code>Beta</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class BetaAlgorithm : QCAlgorithm
{
    private Symbol _symbol,_reference;
    private Beta _b;

    public override void Initialize()
    {
        _symbol = AddEquity("QQQ", Resolution.Daily).Symbol;
        _reference = AddEquity("SPY", Resolution.Daily).Symbol;
        _b = B(_symbol, _reference, 20);
    }

    public override void OnData(Slice data)
    {

        if (_b.IsReady)
        {
            // The current value of _b is represented by itself (_b)
            // or _b.Current.Value
            Plot("Beta", "b", _b);
        }
    }
}</pre>
<pre class="python">class BetaAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("QQQ", Resolution.DAILY).symbol
        self._reference = self.add_equity("SPY", Resolution.DAILY).symbol
        self._b = self.b(self._symbol, self._reference, 20)

    def on_data(self, slice: Slice) -> None:

        if self._b.is_ready:
            # The current value of self._b is represented by self._b.current.value
            self.plot("Beta", "b", self._b.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.b">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>Beta</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class BetaAlgorithm : QCAlgorithm
{
    private Symbol _symbol,_reference;
    private Beta _beta;

    public override void Initialize()
    {
        _symbol = AddEquity("QQQ", Resolution.Daily).Symbol;
        _reference = AddEquity("SPY", Resolution.Daily).Symbol;
        _beta = new Beta("", _symbol, _reference, 20);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _beta.Update(bar.EndTime, bar.Close);
        if (data.Bars.TryGetValue(_reference, out var bar))
            _beta.Update(bar.EndTime, bar.Close);

        if (_beta.IsReady)
        {
            // The current value of _beta is represented by itself (_beta)
            // or _beta.Current.Value
            Plot("Beta", "beta", _beta);
        }
    }
}</pre>
<pre class="python">class BetaAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("QQQ", Resolution.DAILY).symbol
        self._reference = self.add_equity("SPY", Resolution.DAILY).symbol
        self._beta = Beta("", self._symbol, self._reference, 20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._beta.update(bar.end_time, bar.close)
        bar = slice.bars.get(self._reference)
        if bar:
            self._beta.update(bar.end_time, bar.close)

        if self._beta.is_ready:
            # The current value of self._beta is represented by self._beta.current.value
            self.plot("Beta", "beta", self._beta.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1Beta.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/Beta">reference</a>.</p>