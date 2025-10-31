<p>To create an automatic indicators for <code>BollingerBands</code>, call the <code class='csharp'>BB</code><code class='python'>bb</code> helper method from the <code>QCAlgorithm</code> class. The <code class='csharp'>BB</code><code class='python'>bb</code> method creates a <code>BollingerBands</code> object, hooks it up for automatic updates, and returns it so you can used it in your algorithm. In most cases, you should call the helper method in the <code class="csharp">Initialize</code><code class="python">initialize</code> method.<p>
<div class="section-example-container">
<pre class="csharp">public class BollingerBandsAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private BollingerBands _bb;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _bb = BB(_symbol, 30, 2m);
    }

    public override void OnData(Slice data)
    {

        if (_bb.IsReady)
        {
            // The current value of _bb is represented by itself (_bb)
            // or _bb.Current.Value
            Plot("BollingerBands", "bb", _bb);
            // Plot all properties of abands
            Plot("BollingerBands", "standarddeviation", _bb.StandardDeviation);
            Plot("BollingerBands", "middleband", _bb.MiddleBand);
            Plot("BollingerBands", "upperband", _bb.UpperBand);
            Plot("BollingerBands", "lowerband", _bb.LowerBand);
            Plot("BollingerBands", "bandwidth", _bb.BandWidth);
            Plot("BollingerBands", "percentb", _bb.PercentB);
            Plot("BollingerBands", "price", _bb.Price);
        }
    }
}</pre>
<pre class="python">class BollingerBandsAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._bb = self.bb(self._symbol, 30, 2)

    def on_data(self, slice: Slice) -> None:

        if self._bb.is_ready:
            # The current value of self._bb is represented by self._bb.current.value
            self.plot("BollingerBands", "bb", self._bb.current.value)
            # Plot all attributes of self._bb
            self.plot("BollingerBands", "standard_deviation", self._bb.standard_deviation.current.value)
            self.plot("BollingerBands", "middle_band", self._bb.middle_band.current.value)
            self.plot("BollingerBands", "upper_band", self._bb.upper_band.current.value)
            self.plot("BollingerBands", "lower_band", self._bb.lower_band.current.value)
            self.plot("BollingerBands", "band_width", self._bb.band_width.current.value)
            self.plot("BollingerBands", "percent_b", self._bb.percent_b.current.value)
            self.plot("BollingerBands", "price", self._bb.price.current.value)</pre></div>
<p>For more information about this method, see the <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Algorithm_1_1QCAlgorithm.html">QCAlgorithm class</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Algorithm/QCAlgorithm/#QuantConnect.Algorithm.QCAlgorithm.bb">QCAlgorithm class</a>.</p>
<p>You can manually create a <code>BollingerBands</code> indicator, so it doesn't automatically update. Manual indicators let you update their values with any data you choose.</p>
<p>Updating your indicator manually enables you to control when the indicator is updated and what data you use to update it. To manually update the indicator, call the <code class="csharp">Update</code><code class="python">update</code> method. The indicator will only be ready after you prime it with enough data.</p>
<div class="section-example-container">
<pre class="csharp">public class BollingerBandsAlgorithm : QCAlgorithm
{
    private Symbol _symbol;
    private BollingerBands _bollingerbands;

    public override void Initialize()
    {
        _symbol = AddEquity("SPY", Resolution.Daily).Symbol;
        _bollingerbands = new BollingerBands(30, 2m);
    }

    public override void OnData(Slice data)
    {
        if (data.Bars.TryGetValue(_symbol, out var bar))
            _bollingerbands.Update(bar.EndTime, bar.Close);

        if (_bollingerbands.IsReady)
        {
            // The current value of _bollingerbands is represented by itself (_bollingerbands)
            // or _bollingerbands.Current.Value
            Plot("BollingerBands", "bollingerbands", _bollingerbands);
            // Plot all properties of abands
            Plot("BollingerBands", "standarddeviation", _bollingerbands.StandardDeviation);
            Plot("BollingerBands", "middleband", _bollingerbands.MiddleBand);
            Plot("BollingerBands", "upperband", _bollingerbands.UpperBand);
            Plot("BollingerBands", "lowerband", _bollingerbands.LowerBand);
            Plot("BollingerBands", "bandwidth", _bollingerbands.BandWidth);
            Plot("BollingerBands", "percentb", _bollingerbands.PercentB);
            Plot("BollingerBands", "price", _bollingerbands.Price);
        }
    }
}</pre>
<pre class="python">class BollingerBandsAlgorithm(QCAlgorithm):
    def initialize(self) -> None:
        self._symbol = self.add_equity("SPY", Resolution.DAILY).symbol
        self._bollingerbands = BollingerBands(30, 2)

    def on_data(self, slice: Slice) -> None:
        bar = slice.bars.get(self._symbol)
        if bar:
            self._bollingerbands.update(bar.end_time, bar.close)

        if self._bollingerbands.is_ready:
            # The current value of self._bollingerbands is represented by self._bollingerbands.current.value
            self.plot("BollingerBands", "bollingerbands", self._bollingerbands.current.value)
            # Plot all attributes of self._bollingerbands
            self.plot("BollingerBands", "standard_deviation", self._bollingerbands.standard_deviation.current.value)
            self.plot("BollingerBands", "middle_band", self._bollingerbands.middle_band.current.value)
            self.plot("BollingerBands", "upper_band", self._bollingerbands.upper_band.current.value)
            self.plot("BollingerBands", "lower_band", self._bollingerbands.lower_band.current.value)
            self.plot("BollingerBands", "band_width", self._bollingerbands.band_width.current.value)
            self.plot("BollingerBands", "percent_b", self._bollingerbands.percent_b.current.value)
            self.plot("BollingerBands", "price", self._bollingerbands.price.current.value)</pre></div>
<p>For more information about this indicator, see its <a rel="nofollow" target="_blank" class='csharp' href="https://www.lean.io/docs/v2/lean-engine/class-reference/cs/classQuantConnect_1_1Indicators_1_1BollingerBands.html">reference</a><a rel="nofollow" target="_blank" class='python' href="https://www.lean.io/docs/v2/lean-engine/class-reference/py/QuantConnect/Indicators/BollingerBands">reference</a>.</p>