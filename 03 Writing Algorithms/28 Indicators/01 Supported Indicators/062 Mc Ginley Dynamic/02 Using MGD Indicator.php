<p>To create an automatic indicators for <code>McGinleyDynamic</code>, call the <code class='csharp'>MGD</code><code class='python'>mgd</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>MGD</code><code class='python'>mgd</code> method creates a <code>McGinleyDynamic</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class McGinleyDynamicAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private McGinleyDynamic _mgd;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _mgd = MGD(_symbol, 10);
    &rcub;

    public override void OnData(Slice data)
    &lcub;

        if (_mgd.IsReady)
        &lcub;
            // The current value of _mgd is represented by itself (_mgd)
            // or _mgd.Current.Value
            Plot("McGinleyDynamic", "mgd", _mgd);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class McGinleyDynamicAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._mgd = self.mgd(self._symbol, 10)

    def on_data(self, slice: Slice) -> None:

        if self._mgd.is_ready:
            # The current value of self._mgd is represented by self._mgd.current.value
            self.plot("McGinleyDynamic", "mgd", self._mgd.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.mgd">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>McGinleyDynamic</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class McGinleyDynamicAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private McGinleyDynamic _mcginleydynamic;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _mcginleydynamic = new McGinleyDynamic(10);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _mcginleydynamic.Update(bar.EndTime, bar.Close);

        if (_mcginleydynamic.IsReady)
        &lcub;
            // The current value of _mcginleydynamic is represented by itself (_mcginleydynamic)
            // or _mcginleydynamic.Current.Value
            Plot("McGinleyDynamic", "mcginleydynamic", _mcginleydynamic);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class McGinleyDynamicAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._mcginleydynamic = McGinleyDynamic(10)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._mcginleydynamic.update(bar.end_time, bar.close)

        if self._mcginleydynamic.is_ready:
            # The current value of self._mcginleydynamic is represented by self._mcginleydynamic.current.value
            self.plot("McGinleyDynamic", "mcginleydynamic", self._mcginleydynamic.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1McGinleyDynamic.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/McGinleyDynamic">reference</a>.</p>