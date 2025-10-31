<p>To create an automatic indicators for <code>FilteredIdentity</code>, call the <code class='csharp'>FilteredIdentity</code><code class='python'>filtered_identity</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>FilteredIdentity</code><code class='python'>filtered_identity</code> method creates a <code>FilteredIdentity</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class FilteredIdentityAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private FilteredIdentity _filteredidentity;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _filteredidentity = FilteredIdentity(_symbol, filter: x =>  (x as TradeBar).Close > (x as TradeBar).Open);
    &rcub;

    public override void OnData(Slice data)
    &lcub;

        if (_filteredidentity.IsReady)
        &lcub;
            // The current value of _filteredidentity is represented by itself (_filteredidentity)
            // or _filteredidentity.Current.Value
            Plot("FilteredIdentity", "filteredidentity", _filteredidentity);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class FilteredIdentityAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._filtered_identity = self.filtered_identity(self._symbol, filter=lambda x: x.close > x.open)

    def on_data(self, slice: Slice) -> None:

        if self._filtered_identity.is_ready:
            # The current value of self._filtered_identity is represented by self._filtered_identity.current.value
            self.plot("FilteredIdentity", "filtered_identity", self._filtered_identity.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.filtered_identity">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>FilteredIdentity</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class FilteredIdentityAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private FilteredIdentity _filteredidentity;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _filteredidentity = new FilteredIdentity("SPY", filter: x =>  (x as TradeBar).Close > (x as TradeBar).Open);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _filteredidentity.Update(bar.EndTime, bar.Close);

        if (_filteredidentity.IsReady)
        &lcub;
            // The current value of _filteredidentity is represented by itself (_filteredidentity)
            // or _filteredidentity.Current.Value
            Plot("FilteredIdentity", "filteredidentity", _filteredidentity);
        &rcub;
    &rcub;
&rcub;</pre>
<pre class="python">class FilteredIdentityAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._filteredidentity = FilteredIdentity("SPY", filter=lambda x: x.close > x.open)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._filteredidentity.update(bar.end_time, bar.close)

        if self._filteredidentity.is_ready:
            # The current value of self._filteredidentity is represented by self._filteredidentity.current.value
            self.plot("FilteredIdentity", "filteredidentity", self._filteredidentity.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1FilteredIdentity.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/FilteredIdentity">reference</a>.</p>