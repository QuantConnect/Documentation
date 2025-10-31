<p>To create an automatic indicators for <code>AccumulationDistribution</code>, call the <code class='csharp'>AD</code><code class='python'>ad</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>AD</code><code class='python'>ad</code> method creates a <code>AccumulationDistribution</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class AccumulationDistributionAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private AccumulationDistribution _ad;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _ad = AD(_symbol);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_ad.IsReady)
        &lcub;
            // The current value of _ad is represented by itself (_ad)
            // or _ad.Current.Value
            Plot("AccumulationDistribution", "ad", _ad);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class AccumulationDistributionAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._ad = self.ad(self._symbol)

    def on_data(self, slice: Slice) -> None:
        if self._ad.is_ready:
            # The current value of self._ad is represented by self._ad.current.value
            self.plot("AccumulationDistribution", "ad", self._ad.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.ad">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>AccumulationDistribution</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class AccumulationDistributionAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private AccumulationDistribution _accumulationdistribution;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _accumulationdistribution = new AccumulationDistribution();
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _accumulationdistribution.Update(bar.EndTime, bar.Close);
        if (_accumulationdistribution.IsReady)
        &lcub;
            // The current value of _accumulationdistribution is represented by itself (_accumulationdistribution)
            // or _accumulationdistribution.Current.Value
            Plot("AccumulationDistribution", "accumulationdistribution", _accumulationdistribution);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class AccumulationDistributionAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._accumulationdistribution = AccumulationDistribution()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._accumulationdistribution.update(bar.end_time, bar.close)
        if self._accumulationdistribution.is_ready:
            # The current value of self._accumulationdistribution is represented by self._accumulationdistribution.current.value
            self.plot("AccumulationDistribution", "accumulationdistribution", self._accumulationdistribution.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1AccumulationDistribution.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/AccumulationDistribution">reference</a>.</p>