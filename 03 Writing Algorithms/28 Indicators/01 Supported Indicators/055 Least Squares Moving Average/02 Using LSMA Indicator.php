<p>To create an automatic indicators for <code>LeastSquaresMovingAverage</code>, call the <code class='csharp'>LSMA</code><code class='python'>lsma</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>LSMA</code><code class='python'>lsma</code> method creates a <code>LeastSquaresMovingAverage</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class LeastSquaresMovingAverageAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private LeastSquaresMovingAverage _lsma;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _lsma = LSMA(_symbol, 20);
    }

    public override void OnData(Slice data)
    {

        if (_lsma.IsReady)
        {
            // The current value of _lsma is represented by itself (_lsma)
            // or _lsma.Current.Value
            Plot("LeastSquaresMovingAverage", "lsma", _lsma);
            // Plot all properties of abands
            Plot("LeastSquaresMovingAverage", "intercept", _lsma.Intercept);
            Plot("LeastSquaresMovingAverage", "slope", _lsma.Slope);
        }
    }
}</pre>
<pre class="python">class LeastSquaresMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._lsma = self.lsma(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:

        if self._lsma.is_ready:
            # The current value of self._lsma is represented by self._lsma.current.value
            self.plot("LeastSquaresMovingAverage", "lsma", self._lsma.current.value)
            # Plot all attributes of self._lsma
            self.plot("LeastSquaresMovingAverage", "intercept", self._lsma.intercept.current.value)
            self.plot("LeastSquaresMovingAverage", "slope", self._lsma.slope.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.lsma">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>LeastSquaresMovingAverage</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class LeastSquaresMovingAverageAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private LeastSquaresMovingAverage _leastsquaresmovingaverage;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _leastsquaresmovingaverage = new LeastSquaresMovingAverage(20);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _leastsquaresmovingaverage.Update(bar.EndTime, bar.Close);

        if (_leastsquaresmovingaverage.IsReady)
        {
            // The current value of _leastsquaresmovingaverage is represented by itself (_leastsquaresmovingaverage)
            // or _leastsquaresmovingaverage.Current.Value
            Plot("LeastSquaresMovingAverage", "leastsquaresmovingaverage", _leastsquaresmovingaverage);
            // Plot all properties of abands
            Plot("LeastSquaresMovingAverage", "intercept", _leastsquaresmovingaverage.Intercept);
            Plot("LeastSquaresMovingAverage", "slope", _leastsquaresmovingaverage.Slope);
        }
    }
}</pre>
<pre class="python">class LeastSquaresMovingAverageAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._leastsquaresmovingaverage = LeastSquaresMovingAverage(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._leastsquaresmovingaverage.update(bar.end_time, bar.close)

        if self._leastsquaresmovingaverage.is_ready:
            # The current value of self._leastsquaresmovingaverage is represented by self._leastsquaresmovingaverage.current.value
            self.plot("LeastSquaresMovingAverage", "leastsquaresmovingaverage", self._leastsquaresmovingaverage.current.value)
            # Plot all attributes of self._leastsquaresmovingaverage
            self.plot("LeastSquaresMovingAverage", "intercept", self._leastsquaresmovingaverage.intercept.current.value)
            self.plot("LeastSquaresMovingAverage", "slope", self._leastsquaresmovingaverage.slope.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1LeastSquaresMovingAverage.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/LeastSquaresMovingAverage">reference</a>.</p>