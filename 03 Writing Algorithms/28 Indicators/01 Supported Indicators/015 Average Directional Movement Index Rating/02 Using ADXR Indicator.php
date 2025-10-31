<p>To create an automatic indicators for <code>AverageDirectionalMovementIndexRating</code>, call the <code class='csharp'>ADXR</code><code class='python'>adxr</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>ADXR</code><code class='python'>adxr</code> method creates a <code>AverageDirectionalMovementIndexRating</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class AverageDirectionalMovementIndexRatingAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private AverageDirectionalMovementIndexRating _adxr;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _adxr = ADXR(_symbol, 20);
    }

    public override void OnData(Slice data)
    {

        if (_adxr.IsReady)
        {
            // The current value of _adxr is represented by itself (_adxr)
            // or _adxr.Current.Value
            Plot("AverageDirectionalMovementIndexRating", "adxr", _adxr);
            // Plot all properties of abands
            Plot("AverageDirectionalMovementIndexRating", "adx", _adxr.Adx);
        }
    }
}</pre>
<pre class="python">class AverageDirectionalMovementIndexRatingAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._adxr = self.adxr(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:

        if self._adxr.is_ready:
            # The current value of self._adxr is represented by self._adxr.current.value
            self.plot("AverageDirectionalMovementIndexRating", "adxr", self._adxr.current.value)
            # Plot all attributes of self._adxr
            self.plot("AverageDirectionalMovementIndexRating", "adx", self._adxr.adx.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.adxr">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>AverageDirectionalMovementIndexRating</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class AverageDirectionalMovementIndexRatingAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private AverageDirectionalMovementIndexRating _averagedirectionalmovementindexrating;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _averagedirectionalmovementindexrating = new AverageDirectionalMovementIndexRating(20);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _averagedirectionalmovementindexrating.Update(bar.EndTime, bar.Close);

        if (_averagedirectionalmovementindexrating.IsReady)
        {
            // The current value of _averagedirectionalmovementindexrating is represented by itself (_averagedirectionalmovementindexrating)
            // or _averagedirectionalmovementindexrating.Current.Value
            Plot("AverageDirectionalMovementIndexRating", "averagedirectionalmovementindexrating", _averagedirectionalmovementindexrating);
            // Plot all properties of abands
            Plot("AverageDirectionalMovementIndexRating", "adx", _averagedirectionalmovementindexrating.Adx);
        }
    }
}</pre>
<pre class="python">class AverageDirectionalMovementIndexRatingAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._averagedirectionalmovementindexrating = AverageDirectionalMovementIndexRating(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._averagedirectionalmovementindexrating.update(bar.end_time, bar.close)

        if self._averagedirectionalmovementindexrating.is_ready:
            # The current value of self._averagedirectionalmovementindexrating is represented by self._averagedirectionalmovementindexrating.current.value
            self.plot("AverageDirectionalMovementIndexRating", "averagedirectionalmovementindexrating", self._averagedirectionalmovementindexrating.current.value)
            # Plot all attributes of self._averagedirectionalmovementindexrating
            self.plot("AverageDirectionalMovementIndexRating", "adx", self._averagedirectionalmovementindexrating.adx.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1AverageDirectionalMovementIndexRating.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/AverageDirectionalMovementIndexRating">reference</a>.</p>