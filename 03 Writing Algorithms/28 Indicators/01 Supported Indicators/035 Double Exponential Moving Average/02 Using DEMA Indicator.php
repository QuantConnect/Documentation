<p>To create an automatic indicators for <code>DoubleExponentialMovingAverage</code>, call the <code class='csharp'>DEMA</code><code class='python'>dema</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>DEMA</code><code class='python'>dema</code> method creates a <code>DoubleExponentialMovingAverage</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class DoubleExponentialMovingAverageAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private DoubleExponentialMovingAverage _dema;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _dema = DEMA(_symbol, 20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_dema.IsReady)
        &lcub;
            // The current value of _dema is represented by itself (_dema)
            // or _dema.Current.Value
            Plot("DoubleExponentialMovingAverage", "dema", _dema);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class DoubleExponentialMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._dema = self.dema(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:
        if self._dema.is_ready:
            # The current value of self._dema is represented by self._dema.current.value
            self.plot("DoubleExponentialMovingAverage", "dema", self._dema.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.dema">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>DoubleExponentialMovingAverage</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class DoubleExponentialMovingAverageAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private DoubleExponentialMovingAverage _doubleexponentialmovingaverage;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _doubleexponentialmovingaverage = new DoubleExponentialMovingAverage(20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _doubleexponentialmovingaverage.Update(bar.EndTime, bar.Close);
        if (_doubleexponentialmovingaverage.IsReady)
        &lcub;
            // The current value of _doubleexponentialmovingaverage is represented by itself (_doubleexponentialmovingaverage)
            // or _doubleexponentialmovingaverage.Current.Value
            Plot("DoubleExponentialMovingAverage", "doubleexponentialmovingaverage", _doubleexponentialmovingaverage);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class DoubleExponentialMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._doubleexponentialmovingaverage = DoubleExponentialMovingAverage(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._doubleexponentialmovingaverage.update(bar.end_time, bar.close)
        if self._doubleexponentialmovingaverage.is_ready:
            # The current value of self._doubleexponentialmovingaverage is represented by self._doubleexponentialmovingaverage.current.value
            self.plot("DoubleExponentialMovingAverage", "doubleexponentialmovingaverage", self._doubleexponentialmovingaverage.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1DoubleExponentialMovingAverage.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/DoubleExponentialMovingAverage">reference</a>.</p>