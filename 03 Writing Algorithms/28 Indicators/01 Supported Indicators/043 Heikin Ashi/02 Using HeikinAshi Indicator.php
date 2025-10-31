<p>To create an automatic indicators for <code>HeikinAshi</code>, call the <code class='csharp'>HeikinAshi</code><code class='python'>heikin_ashi</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>HeikinAshi</code><code class='python'>heikin_ashi</code> method creates a <code>HeikinAshi</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class HeikinAshiAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private HeikinAshi _heikinashi;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _heikinashi = HeikinAshi(_symbol);
    &rcub;

    public override void OnData(Slice data)
    &lcub;

        if (_heikinashi.IsReady)
        &lcub;
            // The current value of _heikinashi is represented by itself (_heikinashi)
            // or _heikinashi.Current.Value
            Plot("HeikinAshi", "heikinashi", _heikinashi);
            // Plot all properties of abands
            Plot("HeikinAshi", "open", _heikinashi.Open);
            Plot("HeikinAshi", "high", _heikinashi.High);
            Plot("HeikinAshi", "low", _heikinashi.Low);
            Plot("HeikinAshi", "close", _heikinashi.Close);
            Plot("HeikinAshi", "volume", _heikinashi.Volume);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class HeikinAshiAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._heikin_ashi = self.heikin_ashi(self._symbol)

    def on_data(self, slice: Slice) -> None:

        if self._heikin_ashi.is_ready:
            # The current value of self._heikin_ashi is represented by self._heikin_ashi.current.value
            self.plot("HeikinAshi", "heikin_ashi", self._heikin_ashi.current.value)
            # Plot all attributes of self._heikin_ashi
            self.plot("HeikinAshi", "open", self._heikin_ashi.open.current.value)
            self.plot("HeikinAshi", "high", self._heikin_ashi.high.current.value)
            self.plot("HeikinAshi", "low", self._heikin_ashi.low.current.value)
            self.plot("HeikinAshi", "close", self._heikin_ashi.close.current.value)
            self.plot("HeikinAshi", "volume", self._heikin_ashi.volume.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.heikin_ashi">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>HeikinAshi</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class HeikinAshiAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private HeikinAshi _heikinashi;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _heikinashi = new HeikinAshi();
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _heikinashi.Update(bar.EndTime, bar.Close);

        if (_heikinashi.IsReady)
        &lcub;
            // The current value of _heikinashi is represented by itself (_heikinashi)
            // or _heikinashi.Current.Value
            Plot("HeikinAshi", "heikinashi", _heikinashi);
            // Plot all properties of abands
            Plot("HeikinAshi", "open", _heikinashi.Open);
            Plot("HeikinAshi", "high", _heikinashi.High);
            Plot("HeikinAshi", "low", _heikinashi.Low);
            Plot("HeikinAshi", "close", _heikinashi.Close);
            Plot("HeikinAshi", "volume", _heikinashi.Volume);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class HeikinAshiAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._heikinashi = HeikinAshi()

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._heikinashi.update(bar.end_time, bar.close)

        if self._heikinashi.is_ready:
            # The current value of self._heikinashi is represented by self._heikinashi.current.value
            self.plot("HeikinAshi", "heikinashi", self._heikinashi.current.value)
            # Plot all attributes of self._heikinashi
            self.plot("HeikinAshi", "open", self._heikinashi.open.current.value)
            self.plot("HeikinAshi", "high", self._heikinashi.high.current.value)
            self.plot("HeikinAshi", "low", self._heikinashi.low.current.value)
            self.plot("HeikinAshi", "close", self._heikinashi.close.current.value)
            self.plot("HeikinAshi", "volume", self._heikinashi.volume.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1HeikinAshi.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/HeikinAshi">reference</a>.</p>