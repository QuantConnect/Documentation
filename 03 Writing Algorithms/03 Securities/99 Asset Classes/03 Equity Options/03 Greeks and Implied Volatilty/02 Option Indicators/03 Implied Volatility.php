<p>Implied Volatility, <script type="math/tex">\sigma</script>, is often interpreted as the market's expectation for the future volatility of a stock and is implied by the price of the stock's options. It is not observable in the market but can be derived from the price of an option.</p>

<h4>Automatic Indicator</h4>
<?
$typeName = "ImpliedVolatility";
$helperMethod = "IV";
include(DOCS_RESOURCES."/option-indicators/automatic-indicator.php"); 
?>

<p>Note that the <code>IV</code> method has extra arguments.</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Argument</th>
            <th>Data Type</th>
            <th>Description</th>
            <th>Default Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>period</code></td>
            <td><code>int</code></td>
            <td>The number of periods used to calculate the historical volatility for comparison.</td>
            <td>252</td>
        </tr>
    </tbody>
</table>

<h4>Manual Indicator</h4>
<?
$typeName = "ImpliedVolatility";
$indicatorPage = "implied-volatility";
include(DOCS_RESOURCES."/option-indicators/manual-indicator.php");
?>

<h4>Volatility Smoothing</h4>
<p>To perform <a href="/docs/v2/writing-algorithms/securities/asset-classes/equity-options/greeks-and-implied-volatility/key-concepts#05-Volatility-Smoothing">IV smoothing</a>, you can call the <code>SetSmoothingFunction</code> method of the <code>ImpliedVolatility</code> object. Note that you must use the mirror-contract constructor.</p>
<div class="section-example-container">
    <pre class="csharp">private ImpliedVolatility _iv;

public override void Initialize()
{
    var option = QuantConnect.Symbol.CreateOption("AAPL", Market.USA, OptionStyle.American, OptionRight.Put, 505m, new DateTime(2014, 6, 27));
    AddOptionContract(option);

    var mirrorOption = QuantConnect.Symbol.CreateOption("AAPL", Market.USA, OptionStyle.American, OptionRight.Call, 505m, new DateTime(2014, 6, 27));
    AddOptionContract(mirrorOption);

    _iv = IV(option, mirrorOption);
    // example: take average of the call-put pair
    _iv.SetSmoothingFunction((iv, mirrorIv) => (iv + mirrorIv) * 0.5m);
}</pre>
    <pre class="python">def Initialize(self):
    option = Symbol.CreateOption("AAPL", Market.USA, OptionStyle.American, OptionRight.Put, 505, datetime(2014, 6, 27))
    self.AddOptionContract(option)

    mirror_option = Symbol.CreateOption("AAPL", Market.USA, OptionStyle.American, OptionRight.Call, 505m, new DateTime(2014, 6, 27))
    AddOptionContract(mirror_option)

    self.iv = self.IV(option, mirror_option)
    # example: take average of the call-put pair
    self.iv.SetSmoothingFunction(lambda iv, mirror_iv: (iv + mirror_iv) * 0.5)
</pre>
</div>