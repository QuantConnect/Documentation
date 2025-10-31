<p>To create an automatic indicators for <code>MesaAdaptiveMovingAverage</code>, call the <code class='csharp'>MAMA</code><code class='python'>mama</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>MAMA</code><code class='python'>mama</code> method creates a <code>MesaAdaptiveMovingAverage</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class MesaAdaptiveMovingAverageAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private MesaAdaptiveMovingAverage _mama;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _mama = MAMA(_symbol, 0.5m, 0.05m);
    &rcub;

    public override void OnData(Slice data)
    &lcub;

        if (_mama.IsReady)
        &lcub;
            // The current value of _mama is represented by itself (_mama)
            // or _mama.Current.Value
            Plot("MesaAdaptiveMovingAverage", "mama", _mama);
            // Plot all properties of abands
            Plot("MesaAdaptiveMovingAverage", "fama", _mama.Fama);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class MesaAdaptiveMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._mama = self.mama(self._symbol, 0.5, 0.05)

    def on_data(self, slice: Slice) -> None:

        if self._mama.is_ready:
            # The current value of self._mama is represented by self._mama.current.value
            self.plot("MesaAdaptiveMovingAverage", "mama", self._mama.current.value)
            # Plot all attributes of self._mama
            self.plot("MesaAdaptiveMovingAverage", "fama", self._mama.fama.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.mama">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>MesaAdaptiveMovingAverage</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class MesaAdaptiveMovingAverageAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private MesaAdaptiveMovingAverage _mesaadaptivemovingaverage;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _mesaadaptivemovingaverage = new MesaAdaptiveMovingAverage(0.5m, 0.05m);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _mesaadaptivemovingaverage.Update(bar.EndTime, bar.Close);

        if (_mesaadaptivemovingaverage.IsReady)
        &lcub;
            // The current value of _mesaadaptivemovingaverage is represented by itself (_mesaadaptivemovingaverage)
            // or _mesaadaptivemovingaverage.Current.Value
            Plot("MesaAdaptiveMovingAverage", "mesaadaptivemovingaverage", _mesaadaptivemovingaverage);
            // Plot all properties of abands
            Plot("MesaAdaptiveMovingAverage", "fama", _mesaadaptivemovingaverage.Fama);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class MesaAdaptiveMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._mesaadaptivemovingaverage = MesaAdaptiveMovingAverage(0.5, 0.05)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._mesaadaptivemovingaverage.update(bar.end_time, bar.close)

        if self._mesaadaptivemovingaverage.is_ready:
            # The current value of self._mesaadaptivemovingaverage is represented by self._mesaadaptivemovingaverage.current.value
            self.plot("MesaAdaptiveMovingAverage", "mesaadaptivemovingaverage", self._mesaadaptivemovingaverage.current.value)
            # Plot all attributes of self._mesaadaptivemovingaverage
            self.plot("MesaAdaptiveMovingAverage", "fama", self._mesaadaptivemovingaverage.fama.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1MesaAdaptiveMovingAverage.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/MesaAdaptiveMovingAverage">reference</a>.</p>