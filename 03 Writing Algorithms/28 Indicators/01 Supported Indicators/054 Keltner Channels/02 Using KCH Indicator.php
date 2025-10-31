<p>To create an automatic indicators for <code>KeltnerChannels</code>, call the <code class='csharp'>KCH</code><code class='python'>kch</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>KCH</code><code class='python'>kch</code> method creates a <code>KeltnerChannels</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class KeltnerChannelsAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private KeltnerChannels _kch;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _kch = KCH(_symbol, 20, 2, MovingAverageType.Simple);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_kch.IsReady)
        &lcub;
            // The current value of _kch is represented by itself (_kch)
            // or _kch.Current.Value
            Plot("KeltnerChannels", "kch", _kch);
            // Plot all properties of abands
            Plot("KeltnerChannels", "middleband", _kch.MiddleBand);
            Plot("KeltnerChannels", "upperband", _kch.UpperBand);
            Plot("KeltnerChannels", "lowerband", _kch.LowerBand);
            Plot("KeltnerChannels", "averagetruerange", _kch.AverageTrueRange);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class KeltnerChannelsAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._kch = self.kch(self._symbol, 20, 2, MovingAverageType.SIMPLE)

    def on_data(self, slice: Slice) -> None:
        if self._kch.is_ready:
            # The current value of self._kch is represented by self._kch.current.value
            self.plot("KeltnerChannels", "kch", self._kch.current.value)
            # Plot all attributes of self._kch
            self.plot("KeltnerChannels", "middle_band", self._kch.middle_band.current.value)
            self.plot("KeltnerChannels", "upper_band", self._kch.upper_band.current.value)
            self.plot("KeltnerChannels", "lower_band", self._kch.lower_band.current.value)
            self.plot("KeltnerChannels", "average_true_range", self._kch.average_True_range.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.kch">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>KeltnerChannels</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class KeltnerChannelsAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private KeltnerChannels _keltnerchannels;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _keltnerchannels = new KeltnerChannels(20, 2, MovingAverageType.Simple);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _keltnerchannels.Update(bar.EndTime, bar.Close);
        if (_keltnerchannels.IsReady)
        &lcub;
            // The current value of _keltnerchannels is represented by itself (_keltnerchannels)
            // or _keltnerchannels.Current.Value
            Plot("KeltnerChannels", "keltnerchannels", _keltnerchannels);
            // Plot all properties of abands
            Plot("KeltnerChannels", "middleband", _keltnerchannels.MiddleBand);
            Plot("KeltnerChannels", "upperband", _keltnerchannels.UpperBand);
            Plot("KeltnerChannels", "lowerband", _keltnerchannels.LowerBand);
            Plot("KeltnerChannels", "averagetruerange", _keltnerchannels.AverageTrueRange);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class KeltnerChannelsAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._keltnerchannels = KeltnerChannels(20, 2, MovingAverageType.SIMPLE)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._keltnerchannels.update(bar.end_time, bar.close)
        if self._keltnerchannels.is_ready:
            # The current value of self._keltnerchannels is represented by self._keltnerchannels.current.value
            self.plot("KeltnerChannels", "keltnerchannels", self._keltnerchannels.current.value)
            # Plot all attributes of self._keltnerchannels
            self.plot("KeltnerChannels", "middle_band", self._keltnerchannels.middle_band.current.value)
            self.plot("KeltnerChannels", "upper_band", self._keltnerchannels.upper_band.current.value)
            self.plot("KeltnerChannels", "lower_band", self._keltnerchannels.lower_band.current.value)
            self.plot("KeltnerChannels", "average_true_range", self._keltnerchannels.average_True_range.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1KeltnerChannels.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/KeltnerChannels">reference</a>.</p>