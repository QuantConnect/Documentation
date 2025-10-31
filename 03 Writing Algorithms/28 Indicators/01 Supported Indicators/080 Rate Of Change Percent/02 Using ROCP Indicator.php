<p>To create an automatic indicators for <code>RateOfChangePercent</code>, call the <code class='csharp'>ROCP</code><code class='python'>rocp</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>ROCP</code><code class='python'>rocp</code> method creates a <code>RateOfChangePercent</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class RateOfChangePercentAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private RateOfChangePercent _rocp;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _rocp = ROCP(_symbol, 10);
    &rcub;

    public override void OnData(Slice data)
    &lcub;

        if (_rocp.IsReady)
        &lcub;
            // The current value of _rocp is represented by itself (_rocp)
            // or _rocp.Current.Value
            Plot("RateOfChangePercent", "rocp", _rocp);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class RateOfChangePercentAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._rocp = self.rocp(self._symbol, 10)

    def on_data(self, slice: Slice) -> None:

        if self._rocp.is_ready:
            # The current value of self._rocp is represented by self._rocp.current.value
            self.plot("RateOfChangePercent", "rocp", self._rocp.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.rocp">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>RateOfChangePercent</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class RateOfChangePercentAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private RateOfChangePercent _rateofchangepercent;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _rateofchangepercent = new RateOfChangePercent(10);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _rateofchangepercent.Update(bar.EndTime, bar.Close);

        if (_rateofchangepercent.IsReady)
        &lcub;
            // The current value of _rateofchangepercent is represented by itself (_rateofchangepercent)
            // or _rateofchangepercent.Current.Value
            Plot("RateOfChangePercent", "rateofchangepercent", _rateofchangepercent);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class RateOfChangePercentAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._rateofchangepercent = RateOfChangePercent(10)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._rateofchangepercent.update(bar.end_time, bar.close)

        if self._rateofchangepercent.is_ready:
            # The current value of self._rateofchangepercent is represented by self._rateofchangepercent.current.value
            self.plot("RateOfChangePercent", "rateofchangepercent", self._rateofchangepercent.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1RateOfChangePercent.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/RateOfChangePercent">reference</a>.</p>