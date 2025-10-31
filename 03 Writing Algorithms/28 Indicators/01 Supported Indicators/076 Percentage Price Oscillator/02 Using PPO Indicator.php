<p>To create an automatic indicators for <code>PercentagePriceOscillator</code>, call the <code class='csharp'>PPO</code><code class='python'>ppo</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>PPO</code><code class='python'>ppo</code> method creates a <code>PercentagePriceOscillator</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class PercentagePriceOscillatorAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private PercentagePriceOscillator _ppo;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _ppo = PPO(_symbol, 10, 20, MovingAverageType.Simple);
    }

    public override void OnData(Slice data)
    {

        if (_ppo.IsReady)
        {
            // The current value of _ppo is represented by itself (_ppo)
            // or _ppo.Current.Value
            Plot("PercentagePriceOscillator", "ppo", _ppo);
            // Plot all properties of abands
            Plot("PercentagePriceOscillator", "fast", _ppo.Fast);
            Plot("PercentagePriceOscillator", "slow", _ppo.Slow);
            Plot("PercentagePriceOscillator", "signal", _ppo.Signal);
            Plot("PercentagePriceOscillator", "histogram", _ppo.Histogram);
        }
    }
}</pre>
<pre class="python">class PercentagePriceOscillatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._ppo = self.ppo(self._symbol, 10, 20, MovingAverageType.SIMPLE)

    def on_data(self, slice: Slice) -> None:

        if self._ppo.is_ready:
            # The current value of self._ppo is represented by self._ppo.current.value
            self.plot("PercentagePriceOscillator", "ppo", self._ppo.current.value)
            # Plot all attributes of self._ppo
            self.plot("PercentagePriceOscillator", "fast", self._ppo.fast.current.value)
            self.plot("PercentagePriceOscillator", "slow", self._ppo.slow.current.value)
            self.plot("PercentagePriceOscillator", "signal", self._ppo.signal.current.value)
            self.plot("PercentagePriceOscillator", "histogram", self._ppo.histogram.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.ppo">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>PercentagePriceOscillator</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class PercentagePriceOscillatorAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private PercentagePriceOscillator _percentagepriceoscillator;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _percentagepriceoscillator = new PercentagePriceOscillator(10, 20, MovingAverageType.Simple);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _percentagepriceoscillator.Update(bar.EndTime, bar.Close);

        if (_percentagepriceoscillator.IsReady)
        {
            // The current value of _percentagepriceoscillator is represented by itself (_percentagepriceoscillator)
            // or _percentagepriceoscillator.Current.Value
            Plot("PercentagePriceOscillator", "percentagepriceoscillator", _percentagepriceoscillator);
            // Plot all properties of abands
            Plot("PercentagePriceOscillator", "fast", _percentagepriceoscillator.Fast);
            Plot("PercentagePriceOscillator", "slow", _percentagepriceoscillator.Slow);
            Plot("PercentagePriceOscillator", "signal", _percentagepriceoscillator.Signal);
            Plot("PercentagePriceOscillator", "histogram", _percentagepriceoscillator.Histogram);
        }
    }
}</pre>
<pre class="python">class PercentagePriceOscillatorAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._percentagepriceoscillator = PercentagePriceOscillator(10, 20, MovingAverageType.SIMPLE)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._percentagepriceoscillator.update(bar.end_time, bar.close)

        if self._percentagepriceoscillator.is_ready:
            # The current value of self._percentagepriceoscillator is represented by self._percentagepriceoscillator.current.value
            self.plot("PercentagePriceOscillator", "percentagepriceoscillator", self._percentagepriceoscillator.current.value)
            # Plot all attributes of self._percentagepriceoscillator
            self.plot("PercentagePriceOscillator", "fast", self._percentagepriceoscillator.fast.current.value)
            self.plot("PercentagePriceOscillator", "slow", self._percentagepriceoscillator.slow.current.value)
            self.plot("PercentagePriceOscillator", "signal", self._percentagepriceoscillator.signal.current.value)
            self.plot("PercentagePriceOscillator", "histogram", self._percentagepriceoscillator.histogram.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1PercentagePriceOscillator.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/PercentagePriceOscillator">reference</a>.</p>