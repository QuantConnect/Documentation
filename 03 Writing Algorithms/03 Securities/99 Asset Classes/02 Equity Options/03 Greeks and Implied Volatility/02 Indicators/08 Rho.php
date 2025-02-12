<p>
    Rho, <script type="math/tex">\rho</script>, is the rate of change of the value of a derivative with respect to the <a href='/docs/v2/writing-algorithms/reality-modeling/risk-free-interest-rate/key-concepts'>interest rate</a>. 
    It is usually small and not a big issue in practice unless the Option is deep in-the-money and has a long horizon. 
    In this case, the interest rate matters because you need to discount a larger cash flow over a longer horizon. 
    For more information about rho, see <a href='/learning/articles/introduction-to-options/the-greek-letters#rho'>Rho</a>.
</p>

<h4>Automatic Indicators</h4>
<?
// Tag: div section-example-container testable
$name = "rho";
$typeName = "Rho";
$helperMethod = "R";

include(DOCS_RESOURCES."/option-indicators/equity.php");
include(DOCS_RESOURCES."/option-indicators/automatic-indicator.php"); 
?>
<div class="regression-test-results">
    <script class="csharp-result" type="text"></script>
    <script class="python-result" type="text"></script>
</div>

<p>The follow table describes the arguments that the <code class='csharp'>R</code><code class='python'>r</code> method accepts in addition to the <a href='/docs/v2/writing-algorithms/securities/asset-classes/equity-options/greeks-and-implied-volatility/indicators#02-Parameters'>standard parameters</a>:</p>

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
                The Option pricing model to use to estimate the IV when calculating rho
                If you don't provide a value, the default value is to match the <code class="csharp">optionModel</code><code class="python">option_model</code> parameter.
            </td>
            <td><code class="csharp">null</code><code class="python">None</code></td>
        </tr>
    </tbody>
</table>


<p>For more information about the <code class='csharp'>R</code><code class='python'>r</code> method, see <a href='/docs/v2/writing-algorithms/indicators/supported-indicators/rho#02-Using-R-Indicator'>Using R Indicator</a>.</p>

<h4>Manual Indicators</h4>
<?
// Tag: div section-example-container testable
$name = "rho";
$typeName = "Rho";
$indicatorPage = "rho";

include(DOCS_RESOURCES."/option-indicators/equity.php");
include(DOCS_RESOURCES."/option-indicators/manual-indicator.php");
?>
<div class="regression-test-results">
    <script class="csharp-result" type="text"></script>
    <script class="python-result" type="text"></script>
</div>

<h4>Volatility Smoothing</h4>
<?
$typeName = "rho";
$ivLink = "/docs/v2/writing-algorithms/securities/asset-classes/equity-options/greeks-and-implied-volatility/indicators#02-Parameters";
include(DOCS_RESOURCES."/option-indicators/iv-smoothing.php");
?>
