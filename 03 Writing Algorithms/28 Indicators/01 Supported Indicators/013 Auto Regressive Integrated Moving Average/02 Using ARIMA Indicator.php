<p>To create an automatic indicators for <code>AutoRegressiveIntegratedMovingAverage</code>, call the <code class='csharp'>ARIMA</code><code class='python'>arima</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>ARIMA</code><code class='python'>arima</code> method creates a <code>AutoRegressiveIntegratedMovingAverage</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class AutoRegressiveIntegratedMovingAverageAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private AutoRegressiveIntegratedMovingAverage _arima;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _arima = ARIMA(_symbol, 1, 1, 1, 20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_arima.IsReady)
        &lcub;
            // The current value of _arima is represented by itself (_arima)
            // or _arima.Current.Value
            Plot("AutoRegressiveIntegratedMovingAverage", "arima", _arima);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class AutoRegressiveIntegratedMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._arima = self.arima(self._symbol, 1, 1, 20)

    def on_data(self, slice: Slice) -> None:
        if self._arima.is_ready:
            # The current value of self._arima is represented by self._arima.current.value
            self.plot("AutoRegressiveIntegratedMovingAverage", "arima", self._arima.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.arima">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>AutoRegressiveIntegratedMovingAverage</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class AutoRegressiveIntegratedMovingAverageAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private AutoRegressiveIntegratedMovingAverage _autoregressiveintegratedmovingaverage;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _autoregressiveintegratedmovingaverage = new AutoRegressiveIntegratedMovingAverage(1, 1, 1, 20, true);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _autoregressiveintegratedmovingaverage.Update(bar.EndTime, bar.Close);
        if (_autoregressiveintegratedmovingaverage.IsReady)
        &lcub;
            // The current value of _autoregressiveintegratedmovingaverage is represented by itself (_autoregressiveintegratedmovingaverage)
            // or _autoregressiveintegratedmovingaverage.Current.Value
            Plot("AutoRegressiveIntegratedMovingAverage", "autoregressiveintegratedmovingaverage", _autoregressiveintegratedmovingaverage);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class AutoRegressiveIntegratedMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._autoregressiveintegratedmovingaverage = AutoRegressiveIntegratedMovingAverage(1, 1, 1, 20, True)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._autoregressiveintegratedmovingaverage.update(bar.end_time, bar.close)
        if self._autoregressiveintegratedmovingaverage.is_ready:
            # The current value of self._autoregressiveintegratedmovingaverage is represented by self._autoregressiveintegratedmovingaverage.current.value
            self.plot("AutoRegressiveIntegratedMovingAverage", "autoregressiveintegratedmovingaverage", self._autoregressiveintegratedmovingaverage.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1AutoRegressiveIntegratedMovingAverage.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/AutoRegressiveIntegratedMovingAverage">reference</a>.</p>