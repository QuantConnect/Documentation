<p>
    Gamma, <script type="math/tex">\Gamma</script>, is the rate of change of the portfolio's delta with respect to the underlying asset's price. 
    It represents the second-order sensitivity of the Option to a movement in the underlying asset's price.
    For more information about Gamma, see <a href='/learning/articles/introduction-to-options/the-greek-letters#gamma'>Gamma</a>.
</p>

<h4>Automatic Indicators</h4>
<?
$name = "gamma";
$typeName = "Gamma";
$helperMethod = "G";
include(DOCS_RESOURCES."/option-indicators/automatic-indicator.php"); 
?>

<p>The follow table describes the arguments that the <code>G</code> method accepts in addition to the <a href='/docs/v2/writing-algorithms/securities/asset-classes/equity-options/greeks-and-implied-volatility/option-indicators#02-Parameters'>standard parameters</a>:</p>


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
            <td><code>ivModel</code></td>
            <td><code>OptionPricingModelType</code></td>
            <td>
                The Option pricing model to use to estimate the IV when calculating Gamma. 
                If you don't provide a value, the default value is to match the <code>optionModel</code> parameter.
            </td>
            <td><code class="csharp">null</code><code class="python">None</code></td>
        </tr>
    </tbody>
</table>

<p>For more information about the <code>G</code> method, see <a href='/docs/v2/writing-algorithms/indicators/supported-indicators/gamma#02-Using-G-Indicator'>Using G Indicator</a>.</p>

<h4>Manual Indicators</h4>
<?
$name = "gamma";
$typeName = "Gamma";
$indicatorPage = "gamma";
include(DOCS_RESOURCES."/option-indicators/manual-indicator.php");
?>

<h4>Volatility Smoothing</h4>
<?
$typeName = "gamma";
include(DOCS_RESOURCES."/option-indicators/iv-smoothing.php");
?>
