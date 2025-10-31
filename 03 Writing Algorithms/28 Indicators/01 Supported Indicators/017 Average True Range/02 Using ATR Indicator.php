<p>To create an automatic indicators for <code>AverageTrueRange</code>, call the <code class='csharp'>ATR</code><code class='python'>atr</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>ATR</code><code class='python'>atr</code> method creates a <code>AverageTrueRange</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class AverageTrueRangeAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private AverageTrueRange _atr;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _atr = ATR(_symbol, 20, MovingAverageType.Simple);
    &rcub;

    public override void OnData(Slice data)
    &lcub;

        if (_atr.IsReady)
        &lcub;
            // The current value of _atr is represented by itself (_atr)
            // or _atr.Current.Value
            Plot("AverageTrueRange", "atr", _atr);
            // Plot all properties of abands
            Plot("AverageTrueRange", "truerange", _atr.TrueRange);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class AverageTrueRangeAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._atr = self.atr(self._symbol, 20, MovingAverageType.SIMPLE)

    def on_data(self, slice: Slice) -> None:

        if self._atr.is_ready:
            # The current value of self._atr is represented by self._atr.current.value
            self.plot("AverageTrueRange", "atr", self._atr.current.value)
            # Plot all attributes of self._atr
            self.plot("AverageTrueRange", "true_range", self._atr.True_range.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.atr">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>AverageTrueRange</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class AverageTrueRangeAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private AverageTrueRange _averagetruerange;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _averagetruerange = new AverageTrueRange(20, MovingAverageType.Simple);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _averagetruerange.Update(bar.EndTime, bar.Close);

        if (_averagetruerange.IsReady)
        &lcub;
            // The current value of _averagetruerange is represented by itself (_averagetruerange)
            // or _averagetruerange.Current.Value
            Plot("AverageTrueRange", "averagetruerange", _averagetruerange);
            // Plot all properties of abands
            Plot("AverageTrueRange", "truerange", _averagetruerange.TrueRange);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class AverageTrueRangeAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._averagetruerange = AverageTrueRange(20, MovingAverageType.SIMPLE)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._averagetruerange.update(bar.end_time, bar.close)

        if self._averagetruerange.is_ready:
            # The current value of self._averagetruerange is represented by self._averagetruerange.current.value
            self.plot("AverageTrueRange", "averagetruerange", self._averagetruerange.current.value)
            # Plot all attributes of self._averagetruerange
            self.plot("AverageTrueRange", "true_range", self._averagetruerange.True_range.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1AverageTrueRange.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/AverageTrueRange">reference</a>.</p>