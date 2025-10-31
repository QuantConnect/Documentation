<p>To create an automatic indicators for <code>RelativeMovingAverage</code>, call the <code class='csharp'>RMA</code><code class='python'>rma</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>RMA</code><code class='python'>rma</code> method creates a <code>RelativeMovingAverage</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class RelativeMovingAverageAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private RelativeMovingAverage _rma;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _rma = RMA(_symbol, 20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_rma.IsReady)
        &lcub;
            // The current value of _rma is represented by itself (_rma)
            // or _rma.Current.Value
            Plot("RelativeMovingAverage", "rma", _rma);
            // Plot all properties of abands
            Plot("RelativeMovingAverage", "shortaverage", _rma.ShortAverage);
            Plot("RelativeMovingAverage", "mediumaverage", _rma.MediumAverage);
            Plot("RelativeMovingAverage", "longaverage", _rma.LongAverage);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class RelativeMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._rma = self.rma(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:
        if self._rma.is_ready:
            # The current value of self._rma is represented by self._rma.current.value
            self.plot("RelativeMovingAverage", "rma", self._rma.current.value)
            # Plot all attributes of self._rma
            self.plot("RelativeMovingAverage", "short_average", self._rma.short_average.current.value)
            self.plot("RelativeMovingAverage", "medium_average", self._rma.medium_average.current.value)
            self.plot("RelativeMovingAverage", "long_average", self._rma.long_average.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.rma">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>RelativeMovingAverage</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class RelativeMovingAverageAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private RelativeMovingAverage _relativemovingaverage;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _relativemovingaverage = new RelativeMovingAverage(20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _relativemovingaverage.Update(bar.EndTime, bar.Close);
        if (_relativemovingaverage.IsReady)
        &lcub;
            // The current value of _relativemovingaverage is represented by itself (_relativemovingaverage)
            // or _relativemovingaverage.Current.Value
            Plot("RelativeMovingAverage", "relativemovingaverage", _relativemovingaverage);
            // Plot all properties of abands
            Plot("RelativeMovingAverage", "shortaverage", _relativemovingaverage.ShortAverage);
            Plot("RelativeMovingAverage", "mediumaverage", _relativemovingaverage.MediumAverage);
            Plot("RelativeMovingAverage", "longaverage", _relativemovingaverage.LongAverage);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class RelativeMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._relativemovingaverage = RelativeMovingAverage(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._relativemovingaverage.update(bar.end_time, bar.close)
        if self._relativemovingaverage.is_ready:
            # The current value of self._relativemovingaverage is represented by self._relativemovingaverage.current.value
            self.plot("RelativeMovingAverage", "relativemovingaverage", self._relativemovingaverage.current.value)
            # Plot all attributes of self._relativemovingaverage
            self.plot("RelativeMovingAverage", "short_average", self._relativemovingaverage.short_average.current.value)
            self.plot("RelativeMovingAverage", "medium_average", self._relativemovingaverage.medium_average.current.value)
            self.plot("RelativeMovingAverage", "long_average", self._relativemovingaverage.long_average.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1RelativeMovingAverage.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/RelativeMovingAverage">reference</a>.</p>