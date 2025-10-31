<p>To create an automatic indicators for <code>SimpleMovingAverage</code>, call the <code class='csharp'>SMA</code><code class='python'>sma</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>SMA</code><code class='python'>sma</code> method creates a <code>SimpleMovingAverage</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class SimpleMovingAverageAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private SimpleMovingAverage _sma;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _sma = SMA(_symbol, 20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_sma.IsReady)
        &lcub;
            // The current value of _sma is represented by itself (_sma)
            // or _sma.Current.Value
            Plot("SimpleMovingAverage", "sma", _sma);
            // Plot all properties of abands
            Plot("SimpleMovingAverage", "rollingsum", _sma.RollingSum);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class SimpleMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._sma = self.sma(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:
        if self._sma.is_ready:
            # The current value of self._sma is represented by self._sma.current.value
            self.plot("SimpleMovingAverage", "sma", self._sma.current.value)
            # Plot all attributes of self._sma
            self.plot("SimpleMovingAverage", "rolling_sum", self._sma.rolling_sum.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.sma">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>SimpleMovingAverage</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class SimpleMovingAverageAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private SimpleMovingAverage _simplemovingaverage;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _simplemovingaverage = new SimpleMovingAverage(20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _simplemovingaverage.Update(bar.EndTime, bar.Close);
        if (_simplemovingaverage.IsReady)
        &lcub;
            // The current value of _simplemovingaverage is represented by itself (_simplemovingaverage)
            // or _simplemovingaverage.Current.Value
            Plot("SimpleMovingAverage", "simplemovingaverage", _simplemovingaverage);
            // Plot all properties of abands
            Plot("SimpleMovingAverage", "rollingsum", _simplemovingaverage.RollingSum);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class SimpleMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._simplemovingaverage = SimpleMovingAverage(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._simplemovingaverage.update(bar.end_time, bar.close)
        if self._simplemovingaverage.is_ready:
            # The current value of self._simplemovingaverage is represented by self._simplemovingaverage.current.value
            self.plot("SimpleMovingAverage", "simplemovingaverage", self._simplemovingaverage.current.value)
            # Plot all attributes of self._simplemovingaverage
            self.plot("SimpleMovingAverage", "rolling_sum", self._simplemovingaverage.rolling_sum.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1SimpleMovingAverage.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/SimpleMovingAverage">reference</a>.</p>