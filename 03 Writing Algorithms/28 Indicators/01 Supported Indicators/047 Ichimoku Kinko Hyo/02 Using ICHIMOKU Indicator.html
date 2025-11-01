<p>To create an automatic indicators for <code>IchimokuKinkoHyo</code>, call the <code class='csharp'>ICHIMOKU</code><code class='python'>ichimoku</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>ICHIMOKU</code><code class='python'>ichimoku</code> method creates a <code>IchimokuKinkoHyo</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container testable">
<pre class="csharp">public class IchimokuKinkoHyoAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private IchimokuKinkoHyo _ichimoku;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _ichimoku = ICHIMOKU(_symbol, 9, 26, 17, 52, 26, 26);
    }

    public override void OnData(Slice data)
    {

        if (_ichimoku.IsReady)
        {
            // The current value of _ichimoku is represented by itself (_ichimoku)
            // or _ichimoku.Current.Value
            Plot("IchimokuKinkoHyo", "ichimoku", _ichimoku);
            // Plot all properties of abands
            Plot("IchimokuKinkoHyo", "tenkan", _ichimoku.Tenkan);
            Plot("IchimokuKinkoHyo", "kijun", _ichimoku.Kijun);
            Plot("IchimokuKinkoHyo", "senkoua", _ichimoku.SenkouA);
            Plot("IchimokuKinkoHyo", "senkoub", _ichimoku.SenkouB);
            Plot("IchimokuKinkoHyo", "chikou", _ichimoku.Chikou);
            Plot("IchimokuKinkoHyo", "tenkanmaximum", _ichimoku.TenkanMaximum);
            Plot("IchimokuKinkoHyo", "tenkanminimum", _ichimoku.TenkanMinimum);
            Plot("IchimokuKinkoHyo", "kijunmaximum", _ichimoku.KijunMaximum);
            Plot("IchimokuKinkoHyo", "kijunminimum", _ichimoku.KijunMinimum);
            Plot("IchimokuKinkoHyo", "senkoubmaximum", _ichimoku.SenkouBMaximum);
            Plot("IchimokuKinkoHyo", "senkoubminimum", _ichimoku.SenkouBMinimum);
            Plot("IchimokuKinkoHyo", "delayedtenkansenkoua", _ichimoku.DelayedTenkanSenkouA);
            Plot("IchimokuKinkoHyo", "delayedkijunsenkoua", _ichimoku.DelayedKijunSenkouA);
            Plot("IchimokuKinkoHyo", "delayedmaximumsenkoub", _ichimoku.DelayedMaximumSenkouB);
            Plot("IchimokuKinkoHyo", "delayedminimumsenkoub", _ichimoku.DelayedMinimumSenkouB);
        }
    }
}</pre>
<pre class="python">class IchimokuKinkoHyoAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._ichimoku = self.ichimoku(self._symbol, 9, 26, 17, 52, 26, 26)

    def on_data(self, slice: Slice) -> None:

        if self._ichimoku.is_ready:
            # The current value of self._ichimoku is represented by self._ichimoku.current.value
            self.plot("IchimokuKinkoHyo", "ichimoku", self._ichimoku.current.value)
            # Plot all attributes of self._ichimoku
            self.plot("IchimokuKinkoHyo", "tenkan", self._ichimoku.tenkan.current.value)
            self.plot("IchimokuKinkoHyo", "kijun", self._ichimoku.kijun.current.value)
            self.plot("IchimokuKinkoHyo", "senkou_a", self._ichimoku.senkou_a.current.value)
            self.plot("IchimokuKinkoHyo", "senkou_b", self._ichimoku.senkou_b.current.value)
            self.plot("IchimokuKinkoHyo", "chikou", self._ichimoku.chikou.current.value)
            self.plot("IchimokuKinkoHyo", "tenkan_maximum", self._ichimoku.tenkan_maximum.current.value)
            self.plot("IchimokuKinkoHyo", "tenkan_minimum", self._ichimoku.tenkan_minimum.current.value)
            self.plot("IchimokuKinkoHyo", "kijun_maximum", self._ichimoku.kijun_maximum.current.value)
            self.plot("IchimokuKinkoHyo", "kijun_minimum", self._ichimoku.kijun_minimum.current.value)
            self.plot("IchimokuKinkoHyo", "senkou_b_maximum", self._ichimoku.senkou_b_maximum.current.value)
            self.plot("IchimokuKinkoHyo", "senkou_b_minimum", self._ichimoku.senkou_b_minimum.current.value)
            self.plot("IchimokuKinkoHyo", "delayed_tenkan_senkou_a", self._ichimoku.delayed_tenkan_senkou_a.current.value)
            self.plot("IchimokuKinkoHyo", "delayed_kijun_senkou_a", self._ichimoku.delayed_kijun_senkou_a.current.value)
            self.plot("IchimokuKinkoHyo", "delayed_maximum_senkou_b", self._ichimoku.delayed_maximum_senkou_b.current.value)
            self.plot("IchimokuKinkoHyo", "delayed_minimum_senkou_b", self._ichimoku.delayed_minimum_senkou_b.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.ichimoku">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>IchimokuKinkoHyo</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container testable">
<pre class="csharp">public class IchimokuKinkoHyoAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private IchimokuKinkoHyo _ichimokukinkohyo;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _ichimokukinkohyo = new IchimokuKinkoHyo(9, 26, 17, 52, 26, 26);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _ichimokukinkohyo.Update(bar.EndTime, bar.Close);

        if (_ichimokukinkohyo.IsReady)
        {
            // The current value of _ichimokukinkohyo is represented by itself (_ichimokukinkohyo)
            // or _ichimokukinkohyo.Current.Value
            Plot("IchimokuKinkoHyo", "ichimokukinkohyo", _ichimokukinkohyo);
            // Plot all properties of abands
            Plot("IchimokuKinkoHyo", "tenkan", _ichimokukinkohyo.Tenkan);
            Plot("IchimokuKinkoHyo", "kijun", _ichimokukinkohyo.Kijun);
            Plot("IchimokuKinkoHyo", "senkoua", _ichimokukinkohyo.SenkouA);
            Plot("IchimokuKinkoHyo", "senkoub", _ichimokukinkohyo.SenkouB);
            Plot("IchimokuKinkoHyo", "chikou", _ichimokukinkohyo.Chikou);
            Plot("IchimokuKinkoHyo", "tenkanmaximum", _ichimokukinkohyo.TenkanMaximum);
            Plot("IchimokuKinkoHyo", "tenkanminimum", _ichimokukinkohyo.TenkanMinimum);
            Plot("IchimokuKinkoHyo", "kijunmaximum", _ichimokukinkohyo.KijunMaximum);
            Plot("IchimokuKinkoHyo", "kijunminimum", _ichimokukinkohyo.KijunMinimum);
            Plot("IchimokuKinkoHyo", "senkoubmaximum", _ichimokukinkohyo.SenkouBMaximum);
            Plot("IchimokuKinkoHyo", "senkoubminimum", _ichimokukinkohyo.SenkouBMinimum);
            Plot("IchimokuKinkoHyo", "delayedtenkansenkoua", _ichimokukinkohyo.DelayedTenkanSenkouA);
            Plot("IchimokuKinkoHyo", "delayedkijunsenkoua", _ichimokukinkohyo.DelayedKijunSenkouA);
            Plot("IchimokuKinkoHyo", "delayedmaximumsenkoub", _ichimokukinkohyo.DelayedMaximumSenkouB);
            Plot("IchimokuKinkoHyo", "delayedminimumsenkoub", _ichimokukinkohyo.DelayedMinimumSenkouB);
        }
    }
}</pre>
<pre class="python">class IchimokuKinkoHyoAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._ichimokukinkohyo = IchimokuKinkoHyo(9, 26, 17, 52, 26, 26)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._ichimokukinkohyo.update(bar.end_time, bar.close)

        if self._ichimokukinkohyo.is_ready:
            # The current value of self._ichimokukinkohyo is represented by self._ichimokukinkohyo.current.value
            self.plot("IchimokuKinkoHyo", "ichimokukinkohyo", self._ichimokukinkohyo.current.value)
            # Plot all attributes of self._ichimokukinkohyo
            self.plot("IchimokuKinkoHyo", "tenkan", self._ichimokukinkohyo.tenkan.current.value)
            self.plot("IchimokuKinkoHyo", "kijun", self._ichimokukinkohyo.kijun.current.value)
            self.plot("IchimokuKinkoHyo", "senkou_a", self._ichimokukinkohyo.senkou_a.current.value)
            self.plot("IchimokuKinkoHyo", "senkou_b", self._ichimokukinkohyo.senkou_b.current.value)
            self.plot("IchimokuKinkoHyo", "chikou", self._ichimokukinkohyo.chikou.current.value)
            self.plot("IchimokuKinkoHyo", "tenkan_maximum", self._ichimokukinkohyo.tenkan_maximum.current.value)
            self.plot("IchimokuKinkoHyo", "tenkan_minimum", self._ichimokukinkohyo.tenkan_minimum.current.value)
            self.plot("IchimokuKinkoHyo", "kijun_maximum", self._ichimokukinkohyo.kijun_maximum.current.value)
            self.plot("IchimokuKinkoHyo", "kijun_minimum", self._ichimokukinkohyo.kijun_minimum.current.value)
            self.plot("IchimokuKinkoHyo", "senkou_b_maximum", self._ichimokukinkohyo.senkou_b_maximum.current.value)
            self.plot("IchimokuKinkoHyo", "senkou_b_minimum", self._ichimokukinkohyo.senkou_b_minimum.current.value)
            self.plot("IchimokuKinkoHyo", "delayed_tenkan_senkou_a", self._ichimokukinkohyo.delayed_tenkan_senkou_a.current.value)
            self.plot("IchimokuKinkoHyo", "delayed_kijun_senkou_a", self._ichimokukinkohyo.delayed_kijun_senkou_a.current.value)
            self.plot("IchimokuKinkoHyo", "delayed_maximum_senkou_b", self._ichimokukinkohyo.delayed_maximum_senkou_b.current.value)
            self.plot("IchimokuKinkoHyo", "delayed_minimum_senkou_b", self._ichimokukinkohyo.delayed_minimum_senkou_b.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1IchimokuKinkoHyo.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/IchimokuKinkoHyo">reference</a>.</p>