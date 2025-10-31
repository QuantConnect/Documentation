<p>To create an automatic indicators for <code>AccelerationBands</code>, call the <code class='csharp'>ABANDS</code><code class='python'>abands</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>ABANDS</code><code class='python'>abands</code> method creates a <code>AccelerationBands</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class AccelerationBandsAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private AccelerationBands _abands;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _abands = ABANDS(_symbol, 10, 4m, MovingAverageType.Simple);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_abands.IsReady)
        &lcub;
            // The current value of _abands is represented by itself (_abands)
            // or _abands.Current.Value
            Plot("AccelerationBands", "abands", _abands);
            // Plot all properties of abands
            Plot("AccelerationBands", "middleband", _abands.MiddleBand);
            Plot("AccelerationBands", "upperband", _abands.UpperBand);
            Plot("AccelerationBands", "lowerband", _abands.LowerBand);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class AccelerationBandsAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._abands = self.abands(self._symbol, 10, 4, MovingAverageType.SIMPLE)

    def on_data(self, slice: Slice) -> None:
        if self._abands.is_ready:
            # The current value of self._abands is represented by self._abands.current.value
            self.plot("AccelerationBands", "abands", self._abands.current.value)
            # Plot all attributes of self._abands
            self.plot("AccelerationBands", "middle_band", self._abands.middle_band.current.value)
            self.plot("AccelerationBands", "upper_band", self._abands.upper_band.current.value)
            self.plot("AccelerationBands", "lower_band", self._abands.lower_band.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.abands">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>AccelerationBands</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class AccelerationBandsAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private AccelerationBands _accelerationbands;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _accelerationbands = new AccelerationBands("", 10, 4m, MovingAverageType.Simple);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _accelerationbands.Update(bar.EndTime, bar.Close);
        if (_accelerationbands.IsReady)
        &lcub;
            // The current value of _accelerationbands is represented by itself (_accelerationbands)
            // or _accelerationbands.Current.Value
            Plot("AccelerationBands", "accelerationbands", _accelerationbands);
            // Plot all properties of abands
            Plot("AccelerationBands", "middleband", _accelerationbands.MiddleBand);
            Plot("AccelerationBands", "upperband", _accelerationbands.UpperBand);
            Plot("AccelerationBands", "lowerband", _accelerationbands.LowerBand);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class AccelerationBandsAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._accelerationbands = AccelerationBands("", 10, 4, MovingAverageType.SIMPLE)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._accelerationbands.update(bar.end_time, bar.close)
        if self._accelerationbands.is_ready:
            # The current value of self._accelerationbands is represented by self._accelerationbands.current.value
            self.plot("AccelerationBands", "accelerationbands", self._accelerationbands.current.value)
            # Plot all attributes of self._accelerationbands
            self.plot("AccelerationBands", "middle_band", self._accelerationbands.middle_band.current.value)
            self.plot("AccelerationBands", "upper_band", self._accelerationbands.upper_band.current.value)
            self.plot("AccelerationBands", "lower_band", self._accelerationbands.lower_band.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1AccelerationBands.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/AccelerationBands">reference</a>.</p>