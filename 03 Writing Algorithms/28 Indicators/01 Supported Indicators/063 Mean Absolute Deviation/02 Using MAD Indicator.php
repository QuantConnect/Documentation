<p>To create an automatic indicators for <code>MeanAbsoluteDeviation</code>, call the <code class='csharp'>MAD</code><code class='python'>mad</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>MAD</code><code class='python'>mad</code> method creates a <code>MeanAbsoluteDeviation</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class MeanAbsoluteDeviationAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private MeanAbsoluteDeviation _mad;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _mad = MAD(_symbol, 20);
    }

    public override void OnData(Slice data)
    {

        if (_mad.IsReady)
        {
            // The current value of _mad is represented by itself (_mad)
            // or _mad.Current.Value
            Plot("MeanAbsoluteDeviation", "mad", _mad);
            // Plot all properties of abands
            Plot("MeanAbsoluteDeviation", "mean", _mad.Mean);
        }
    }
}</pre>
<pre class="python">class MeanAbsoluteDeviationAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._mad = self.mad(self._symbol, 20)

    def on_data(self, slice: Slice) -> None:

        if self._mad.is_ready:
            # The current value of self._mad is represented by self._mad.current.value
            self.plot("MeanAbsoluteDeviation", "mad", self._mad.current.value)
            # Plot all attributes of self._mad
            self.plot("MeanAbsoluteDeviation", "mean", self._mad.mean.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.mad">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>MeanAbsoluteDeviation</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class MeanAbsoluteDeviationAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private MeanAbsoluteDeviation _meanabsolutedeviation;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _meanabsolutedeviation = new MeanAbsoluteDeviation(20);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _meanabsolutedeviation.Update(bar.EndTime, bar.Close);

        if (_meanabsolutedeviation.IsReady)
        {
            // The current value of _meanabsolutedeviation is represented by itself (_meanabsolutedeviation)
            // or _meanabsolutedeviation.Current.Value
            Plot("MeanAbsoluteDeviation", "meanabsolutedeviation", _meanabsolutedeviation);
            // Plot all properties of abands
            Plot("MeanAbsoluteDeviation", "mean", _meanabsolutedeviation.Mean);
        }
    }
}</pre>
<pre class="python">class MeanAbsoluteDeviationAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._meanabsolutedeviation = MeanAbsoluteDeviation(20)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._meanabsolutedeviation.update(bar.end_time, bar.close)

        if self._meanabsolutedeviation.is_ready:
            # The current value of self._meanabsolutedeviation is represented by self._meanabsolutedeviation.current.value
            self.plot("MeanAbsoluteDeviation", "meanabsolutedeviation", self._meanabsolutedeviation.current.value)
            # Plot all attributes of self._meanabsolutedeviation
            self.plot("MeanAbsoluteDeviation", "mean", self._meanabsolutedeviation.mean.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1MeanAbsoluteDeviation.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/MeanAbsoluteDeviation">reference</a>.</p>