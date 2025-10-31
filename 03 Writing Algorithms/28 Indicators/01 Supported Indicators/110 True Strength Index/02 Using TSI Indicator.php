<p>To create an automatic indicators for <code>TrueStrengthIndex</code>, call the <code class='csharp'>TSI</code><code class='python'>tsi</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>TSI</code><code class='python'>tsi</code> method creates a <code>TrueStrengthIndex</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class TrueStrengthIndexAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private TrueStrengthIndex _tsi;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _tsi = TSI(_symbol, 25, 13, 7, MovingAverageType.Exponential);
    }

    public override void OnData(Slice data)
    {

        if (_tsi.IsReady)
        {
            // The current value of _tsi is represented by itself (_tsi)
            // or _tsi.Current.Value
            Plot("TrueStrengthIndex", "tsi", _tsi);
            // Plot all properties of abands
            Plot("TrueStrengthIndex", "signal", _tsi.Signal);
        }
    }
}</pre>
<pre class="python">class TrueStrengthIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._tsi = self.tsi(self._symbol, 25, 13, 7, MovingAverageType.EXPONENTIAL)

    def on_data(self, slice: Slice) -> None:

        if self._tsi.is_ready:
            # The current value of self._tsi is represented by self._tsi.current.value
            self.plot("TrueStrengthIndex", "tsi", self._tsi.current.value)
            # Plot all attributes of self._tsi
            self.plot("TrueStrengthIndex", "signal", self._tsi.signal.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.tsi">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>TrueStrengthIndex</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class TrueStrengthIndexAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private TrueStrengthIndex _truestrengthindex;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _truestrengthindex = new TrueStrengthIndex(25, 13, 7, MovingAverageType.Exponential);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _truestrengthindex.Update(bar.EndTime, bar.Close);

        if (_truestrengthindex.IsReady)
        {
            // The current value of _truestrengthindex is represented by itself (_truestrengthindex)
            // or _truestrengthindex.Current.Value
            Plot("TrueStrengthIndex", "truestrengthindex", _truestrengthindex);
            // Plot all properties of abands
            Plot("TrueStrengthIndex", "signal", _truestrengthindex.Signal);
        }
    }
}</pre>
<pre class="python">class TrueStrengthIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._truestrengthindex = TrueStrengthIndex(25, 13, 7, MovingAverageType.EXPONENTIAL)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._truestrengthindex.update(bar.end_time, bar.close)

        if self._truestrengthindex.is_ready:
            # The current value of self._truestrengthindex is represented by self._truestrengthindex.current.value
            self.plot("TrueStrengthIndex", "truestrengthindex", self._truestrengthindex.current.value)
            # Plot all attributes of self._truestrengthindex
            self.plot("TrueStrengthIndex", "signal", self._truestrengthindex.signal.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1TrueStrengthIndex.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/TrueStrengthIndex">reference</a>.</p>