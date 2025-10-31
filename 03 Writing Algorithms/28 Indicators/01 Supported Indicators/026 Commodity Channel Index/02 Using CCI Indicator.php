<p>To create an automatic indicators for <code>CommodityChannelIndex</code>, call the <code class='csharp'>CCI</code><code class='python'>cci</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>CCI</code><code class='python'>cci</code> method creates a <code>CommodityChannelIndex</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p><div class="section-example-container">
    <pre class="csharp">public class CommodityChannelIndexAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private CommodityChannelIndex _cci;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _cci = CCI(_symbol, 20, MovingAverageType.Simple);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (_cci.IsReady)
        &lcub;
            // The current value of _cci is represented by itself (_cci)
            // or _cci.Current.Value
            Plot("CommodityChannelIndex", "cci", _cci);
            // Plot all properties of abands
            Plot("CommodityChannelIndex", "typicalpriceaverage", _cci.TypicalPriceAverage);
            Plot("CommodityChannelIndex", "typicalpricemeandeviation", _cci.TypicalPriceMeanDeviation);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class CommodityChannelIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._cci = self.cci(self._symbol, 20, MovingAverageType.SIMPLE)

    def on_data(self, slice: Slice) -> None:
        if self._cci.is_ready:
            # The current value of self._cci is represented by self._cci.current.value
            self.plot("CommodityChannelIndex", "cci", self._cci.current.value)
            # Plot all attributes of self._cci
            self.plot("CommodityChannelIndex", "typical_price_average", self._cci.typical_price_average.current.value)
            self.plot("CommodityChannelIndex", "typical_price_mean_deviation", self._cci.typical_price_mean_deviation.current.value)</pre></div>
<p>For more information about this method, see the <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.cci">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>CommodityChannelIndex</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p><div class="section-example-container">
    <pre class="csharp">public class CommodityChannelIndexAlgorithm : QCAlgorithm
&lcub;
    private Symbol _symbol;
    private CommodityChannelIndex _commoditychannelindex;

    public override void Initialize()
    &lcub;
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _commoditychannelindex = new CommodityChannelIndex(20, MovingAverageType.Simple);
    &rcub;

    public override void OnData(Slice data)
    &lcub;
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _commoditychannelindex.Update(bar.EndTime, bar.Close);
        if (_commoditychannelindex.IsReady)
        &lcub;
            // The current value of _commoditychannelindex is represented by itself (_commoditychannelindex)
            // or _commoditychannelindex.Current.Value
            Plot("CommodityChannelIndex", "commoditychannelindex", _commoditychannelindex);
            // Plot all properties of abands
            Plot("CommodityChannelIndex", "typicalpriceaverage", _commoditychannelindex.TypicalPriceAverage);
            Plot("CommodityChannelIndex", "typicalpricemeandeviation", _commoditychannelindex.TypicalPriceMeanDeviation);
        &rcub;
    &rcub;
&rcub;</pre>
    <pre class="python">class CommodityChannelIndexAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._commoditychannelindex = CommodityChannelIndex(20, MovingAverageType.SIMPLE)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._commoditychannelindex.update(bar.end_time, bar.close)
        if self._commoditychannelindex.is_ready:
            # The current value of self._commoditychannelindex is represented by self._commoditychannelindex.current.value
            self.plot("CommodityChannelIndex", "commoditychannelindex", self._commoditychannelindex.current.value)
            # Plot all attributes of self._commoditychannelindex
            self.plot("CommodityChannelIndex", "typical_price_average", self._commoditychannelindex.typical_price_average.current.value)
            self.plot("CommodityChannelIndex", "typical_price_mean_deviation", self._commoditychannelindex.typical_price_mean_deviation.current.value)</pre></div>
<p>For more information about this indicator, see its <a class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1CommodityChannelIndex.html">reference</a><a class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/CommodityChannelIndex">reference</a>.</p>