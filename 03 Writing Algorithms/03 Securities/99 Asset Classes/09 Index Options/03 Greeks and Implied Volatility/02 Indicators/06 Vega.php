<p>
    Vega, <script type="math/tex">\nu</script>, is the rate of change in the value of the Option with respect to the volatility of the underlying asset.
    For more information about vega, see <a href='/learning/articles/introduction-to-options/the-greek-letters#vega'>Vega</a>.
</p>

<h4>Automatic Indicators</h4>
<?
// Tag: div section-example-container testable
$name = "vega";
$typeName = "Vega";
$helperMethod = "V";
include(DOCS_RESOURCES."/option-indicators/index.php");
include(DOCS_RESOURCES."/option-indicators/automatic-indicator.php"); 
?>
<div class="regression-test-results">
    <script class="csharp-result" type="text"></script>
    <script class="python-result" type="text"></script>
</div>

<p>The follow table describes the arguments that the <code class='csharp'>V</code><code class='python'>v</code> method accepts in addition to the <a href='/docs/v2/writing-algorithms/securities/asset-classes/index-options/greeks-and-implied-volatility/indicators#02-Parameters'>standard parameters</a>:</p>


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
            <td><code class="csharp">ivModel</code><code class="python">iv_model</code></td>
            <td><code>OptionPricingModelType</code></td>
            <td>
                The Option pricing model to use to estimate the IV when calculating Vega. 
                If you don't provide a value, the default value is to match the <code class="csharp">optionModel</code><code class="python">option_model</code> parameter.
            </td>
            <td><code class="csharp">null</code><code class="python">None</code></td>
        </tr>
    </tbody>
</table>


<p>For more information about the <code class='csharp'>V</code><code class='python'>v</code> method, see <a href='/docs/v2/writing-algorithms/indicators/supported-indicators/vega#02-Using-V-Indicator'>Using V Indicator</a>.</p>

<h4>Manual Indicators</h4>
<?
// Tag: div section-example-container testable
$name = "vega";
$typeName = "Vega";
$indicatorPage = "vega";
include(DOCS_RESOURCES."/option-indicators/index.php");
include(DOCS_RESOURCES."/option-indicators/manual-indicator.php");
?>
<div class="regression-test-results">
    <script class="csharp-result" type="text"></script>
    <script class="python-result" type="text"></script>
</div>

<h4>Volatility Smoothing</h4>
<?
$name = "vega";
$typeName = "vega";
$ivLink = "/docs/v2/writing-algorithms/securities/asset-classes/index-options/greeks-and-implied-volatility/indicators#02-Parameters";
include(DOCS_RESOURCES."/option-indicators/iv-smoothing.php");
?>
