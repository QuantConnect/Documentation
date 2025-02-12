<p>
    Implied volatility, <script type="math/tex">\sigma</script>, is the market's expectation for the future volatility of an asset and is implied by the price of the assets's Options contracts. 
    You can't observe it in the market but you can derive it from the price of an Option.
    For more information about implied volatility, see <a href='/learning/articles/introduction-to-options/historical-volatility-and-implied-volatility#implied-volatility'>Implied Volatility</a>.
</p>

<h4>Automatic Indicators</h4>
<?
// Tag: div section-example-container testable
$name = "implied volatility";
$typeName = "ImpliedVolatility";
$helperMethod = "IV";

include(DOCS_RESOURCES."/option-indicators/equity.php");
include(DOCS_RESOURCES."/option-indicators/automatic-indicator.php"); 
?>
<div class="regression-test-results">
    <script class="csharp-result" type="text"></script>
    <script class="python-result" type="text"></script>
</div>

<p>For more information about the <code class="csharp">IV</code><code class="python">iv</code> method, see <a href='/docs/v2/writing-algorithms/securities/asset-classes/equity-options/greeks-and-implied-volatility/indicators#02-Parameters'>Parameters</a> and <a href='/docs/v2/writing-algorithms/indicators/supported-indicators/implied-volatility#02-Using-IV-Indicator'>Using IV Indicator</a>.</p>

<h4>Manual Indicators</h4>
<?
// Tag: div section-example-container testable
$name = "implied volatility";
$typeName = "ImpliedVolatility";
$indicatorPage = "implied-volatility";
$helperMethod = "IV";

include(DOCS_RESOURCES."/option-indicators/equity.php");
include(DOCS_RESOURCES."/option-indicators/manual-indicator.php");
?>
<div class="regression-test-results">
    <script class="csharp-result" type="text"></script>
    <script class="python-result" type="text"></script>
</div>

<h4>Volatility Smoothing</h4>
<p>
    The default <a href="/docs/v2/writing-algorithms/securities/asset-classes/equity-options/greeks-and-implied-volatility/indicators#02-Parameters">IV smoothing</a> method uses the one contract in the pair that's at-the-money or out-of-money to calculate the IV.
    To change the smoothing function, pass a <code class="csharp">mirrorOption</code><code class="python">mirror_option</code> argument to the <code class="csharp">IV</code><code class="python">iv</code> method or <code>ImpliedVolatility</code> constructor and then call the <code class="csharp">SetSmoothingFunction</code><code class="python">set_smoothing_function</code> method of the resulting <code>ImpliedVolatility</code> object.
    The follow table describes the arguments of the custom function:
</p>

<table class="qc-table table">
    <thead>
        <tr>
            <th>Argument</th>
            <th>Data Type</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>iv</code></td>
            <td><code class="csharp">decimal</code><code class="python">float</code></td>
            <td>The IV of the Option contract.</td>
        </tr>
        <tr>
            <td><code class="csharp">mirrorIv</code><code class="python">mirror_iv</code></td>
            <td><code class="csharp">decimal</code><code class="python">float</code></td>
            <td>The IV of the mirror Option contract.</td>
        </tr>
    </tbody>
</table>

<p>The method must return a <code class="csharp">decimal</code><code class="python">float</code> as the smoothened IV.</p>

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
    <pre class="python">def initialize(self):
    option = Symbol.create_option("AAPL", Market.USA, OptionStyle.AMERICAN, OptionRight.PUT, 505, datetime(2014, 6, 27))
    self.add_option_contract(option)

    mirror_option = Symbol.create_option("AAPL", Market.USA, OptionStyle.AMERICAN, OptionRight.CALL, 505, datetime(2014, 6, 27))
    self.add_option_contract(mirror_option)

    self._iv = self.iv(option, mirror_option)
    # Example: The average of the call-put pair.
    self._iv.set_smoothing_function(lambda iv, mirror_iv: (iv + mirror_iv) * 0.5)
</pre>
</div>
