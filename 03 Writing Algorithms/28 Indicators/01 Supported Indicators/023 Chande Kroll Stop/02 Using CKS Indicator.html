<p>To create an automatic indicators for <code>ChandeKrollStop</code>, call the <code class='csharp'>CKS</code><code class='python'>cks</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>CKS</code><code class='python'>cks</code> method creates a <code>ChandeKrollStop</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class ChandeKrollStopAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private ChandeKrollStop _cks;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _cks = CKS(_symbol, 10, 1, 9);
    }

    public override void OnData(Slice data)
    {

        if (_cks.IsReady)
        {
            // The current value of _cks is represented by itself (_cks)
            // or _cks.Current.Value
            Plot("ChandeKrollStop", "cks", _cks);
            // Plot all properties of abands
            Plot("ChandeKrollStop", "shortstop", _cks.ShortStop);
            Plot("ChandeKrollStop", "longstop", _cks.LongStop);
        }
    }
}</pre>
<pre class="python">class ChandeKrollStopAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._cks = self.cks(self._symbol, 10, 1, 9)

    def on_data(self, slice: Slice) -> None:

        if self._cks.is_ready:
            # The current value of self._cks is represented by self._cks.current.value
            self.plot("ChandeKrollStop", "cks", self._cks.current.value)
            # Plot all attributes of self._cks
            self.plot("ChandeKrollStop", "short_stop", self._cks.short_stop.current.value)
            self.plot("ChandeKrollStop", "long_stop", self._cks.long_stop.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.cks">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>ChandeKrollStop</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class ChandeKrollStopAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private ChandeKrollStop _chandekrollstop;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _chandekrollstop = new ChandeKrollStop(10, 1, 9);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _chandekrollstop.Update(bar.EndTime, bar.Close);

        if (_chandekrollstop.IsReady)
        {
            // The current value of _chandekrollstop is represented by itself (_chandekrollstop)
            // or _chandekrollstop.Current.Value
            Plot("ChandeKrollStop", "chandekrollstop", _chandekrollstop);
            // Plot all properties of abands
            Plot("ChandeKrollStop", "shortstop", _chandekrollstop.ShortStop);
            Plot("ChandeKrollStop", "longstop", _chandekrollstop.LongStop);
        }
    }
}</pre>
<pre class="python">class ChandeKrollStopAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._chandekrollstop = ChandeKrollStop(10, 1, 9)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._chandekrollstop.update(bar.end_time, bar.close)

        if self._chandekrollstop.is_ready:
            # The current value of self._chandekrollstop is represented by self._chandekrollstop.current.value
            self.plot("ChandeKrollStop", "chandekrollstop", self._chandekrollstop.current.value)
            # Plot all attributes of self._chandekrollstop
            self.plot("ChandeKrollStop", "short_stop", self._chandekrollstop.short_stop.current.value)
            self.plot("ChandeKrollStop", "long_stop", self._chandekrollstop.long_stop.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1ChandeKrollStop.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/ChandeKrollStop">reference</a>.</p>