<p>To create an automatic indicators for <code>ConnorsRelativeStrengthIndex</code>, call the <code class='csharp'>CRSI</code><code class='python'>crsi</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>CRSI</code><code class='python'>crsi</code> method creates a <code>ConnorsRelativeStrengthIndex</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class ConnorsRelativeStrengthIndexAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private ConnorsRelativeStrengthIndex _crsi;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _crsi = CRSI(_symbol, 3, 2, 100);
    }

    public override void OnData(Slice data)
    {

        if (_crsi.IsReady)
        {
            // The current value of _crsi is represented by itself (_crsi)
            // or _crsi.Current.Value
            Plot("ConnorsRelativeStrengthIndex", "crsi", _crsi);
        }
    }
}</pre>
<pre class="python">class ConnorsRelativeStrengthIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._crsi = self.crsi(self._symbol, 3, 2, 100)

    def on_data(self, slice: Slice) -> None:

        if self._crsi.is_ready:
            # The current value of self._crsi is represented by self._crsi.current.value
            self.plot("ConnorsRelativeStrengthIndex", "crsi", self._crsi.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.crsi">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>ConnorsRelativeStrengthIndex</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class ConnorsRelativeStrengthIndexAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private ConnorsRelativeStrengthIndex _connorsrelativestrengthindex;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _connorsrelativestrengthindex = new ConnorsRelativeStrengthIndex(3, 2, 100);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _connorsrelativestrengthindex.Update(bar.EndTime, bar.Close);

        if (_connorsrelativestrengthindex.IsReady)
        {
            // The current value of _connorsrelativestrengthindex is represented by itself (_connorsrelativestrengthindex)
            // or _connorsrelativestrengthindex.Current.Value
            Plot("ConnorsRelativeStrengthIndex", "connorsrelativestrengthindex", _connorsrelativestrengthindex);
        }
    }
}</pre>
<pre class="python">class ConnorsRelativeStrengthIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._connorsrelativestrengthindex = ConnorsRelativeStrengthIndex(3, 2, 100)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._connorsrelativestrengthindex.update(bar.end_time, bar.close)

        if self._connorsrelativestrengthindex.is_ready:
            # The current value of self._connorsrelativestrengthindex is represented by self._connorsrelativestrengthindex.current.value
            self.plot("ConnorsRelativeStrengthIndex", "connorsrelativestrengthindex", self._connorsrelativestrengthindex.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1ConnorsRelativeStrengthIndex.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/ConnorsRelativeStrengthIndex">reference</a>.</p>