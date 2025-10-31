<p>To create an automatic indicators for <code>LinearWeightedMovingAverage</code>, call the <code class='csharp'>LWMA</code><code class='python'>lwma</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>LWMA</code><code class='python'>lwma</code> method creates a <code>LinearWeightedMovingAverage</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class LinearWeightedMovingAverageAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private LinearWeightedMovingAverage _lwma;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _lwma = LWMA(_symbol, 20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;

        if (_lwma.IsReady)
        &lcub;
            // The current value of _lwma is represented by itself (_lwma)
            // or _lwma.Current.Value
            Plot("LinearWeightedMovingAverage", "lwma", _lwma);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class LinearWeightedMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._lwma = self.lwma(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:

        if self._lwma.is_ready:
            # The current value of self._lwma is represented by self._lwma.current.value
            self.plot("LinearWeightedMovingAverage", "lwma", self._lwma.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.lwma">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>LinearWeightedMovingAverage</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class LinearWeightedMovingAverageAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private LinearWeightedMovingAverage _linearweightedmovingaverage;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _linearweightedmovingaverage = new LinearWeightedMovingAverage(20);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _linearweightedmovingaverage.Update(bar.EndTime, bar.Close);

        if (_linearweightedmovingaverage.IsReady)
        &lcub;
            // The current value of _linearweightedmovingaverage is represented by itself (_linearweightedmovingaverage)
            // or _linearweightedmovingaverage.Current.Value
            Plot("LinearWeightedMovingAverage", "linearweightedmovingaverage", _linearweightedmovingaverage);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class LinearWeightedMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._linearweightedmovingaverage = LinearWeightedMovingAverage(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._linearweightedmovingaverage.update(bar.end_time, bar.close)

        if self._linearweightedmovingaverage.is_ready:
            # The current value of self._linearweightedmovingaverage is represented by self._linearweightedmovingaverage.current.value
            self.plot("LinearWeightedMovingAverage", "linearweightedmovingaverage", self._linearweightedmovingaverage.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1LinearWeightedMovingAverage.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/LinearWeightedMovingAverage">reference</a>.</p>