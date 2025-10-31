<p>To create an automatic indicators for <code>StochasticRelativeStrengthIndex</code>, call the <code class='csharp'>SRSI</code><code class='python'>srsi</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>SRSI</code><code class='python'>srsi</code> method creates a <code>StochasticRelativeStrengthIndex</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class StochasticRelativeStrengthIndexAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private StochasticRelativeStrengthIndex _srsi;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _srsi = SRSI(_symbol, 14, 14, 3, 3);
    }

    public override void OnData(Slice data)
    {

        if (_srsi.IsReady)
        {
            // The current value of _srsi is represented by itself (_srsi)
            // or _srsi.Current.Value
            Plot("StochasticRelativeStrengthIndex", "srsi", _srsi);
            // Plot all properties of abands
            Plot("StochasticRelativeStrengthIndex", "k", _srsi.K);
            Plot("StochasticRelativeStrengthIndex", "d", _srsi.D);
        }
    }
}</pre>
<pre class="python">class StochasticRelativeStrengthIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._srsi = self.srsi(self._symbol, 14, 14, 3, 3)

    def on_data(self, slice: Slice) -> None:

        if self._srsi.is_ready:
            # The current value of self._srsi is represented by self._srsi.current.value
            self.plot("StochasticRelativeStrengthIndex", "srsi", self._srsi.current.value)
            # Plot all attributes of self._srsi
            self.plot("StochasticRelativeStrengthIndex", "k", self._srsi.k.current.value)
            self.plot("StochasticRelativeStrengthIndex", "d", self._srsi.d.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.srsi">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>StochasticRelativeStrengthIndex</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class StochasticRelativeStrengthIndexAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private StochasticRelativeStrengthIndex _stochasticrelativestrengthindex;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _stochasticrelativestrengthindex = new StochasticRelativeStrengthIndex(14, 14, 3, 3);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _stochasticrelativestrengthindex.Update(bar.EndTime, bar.Close);

        if (_stochasticrelativestrengthindex.IsReady)
        {
            // The current value of _stochasticrelativestrengthindex is represented by itself (_stochasticrelativestrengthindex)
            // or _stochasticrelativestrengthindex.Current.Value
            Plot("StochasticRelativeStrengthIndex", "stochasticrelativestrengthindex", _stochasticrelativestrengthindex);
            // Plot all properties of abands
            Plot("StochasticRelativeStrengthIndex", "k", _stochasticrelativestrengthindex.K);
            Plot("StochasticRelativeStrengthIndex", "d", _stochasticrelativestrengthindex.D);
        }
    }
}</pre>
<pre class="python">class StochasticRelativeStrengthIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._stochasticrelativestrengthindex = StochasticRelativeStrengthIndex(14, 14, 3, 3)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._stochasticrelativestrengthindex.update(bar.end_time, bar.close)

        if self._stochasticrelativestrengthindex.is_ready:
            # The current value of self._stochasticrelativestrengthindex is represented by self._stochasticrelativestrengthindex.current.value
            self.plot("StochasticRelativeStrengthIndex", "stochasticrelativestrengthindex", self._stochasticrelativestrengthindex.current.value)
            # Plot all attributes of self._stochasticrelativestrengthindex
            self.plot("StochasticRelativeStrengthIndex", "k", self._stochasticrelativestrengthindex.k.current.value)
            self.plot("StochasticRelativeStrengthIndex", "d", self._stochasticrelativestrengthindex.d.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1StochasticRelativeStrengthIndex.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/StochasticRelativeStrengthIndex">reference</a>.</p>