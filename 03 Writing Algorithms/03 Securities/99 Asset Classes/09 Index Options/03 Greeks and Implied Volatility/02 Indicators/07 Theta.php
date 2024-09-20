<p>
    Theta, <script type="math/tex">\Theta</script>, is the rate of change of the value of the Option with respect to the passage of time. 
    It is also known to as the time decay of an Option.
    For more information about theta, see <a href='/learning/articles/introduction-to-options/the-greek-letters#theta'>Theta</a>.
</p>

<h4>Automatic Indicators</h4>
<?
$name = "theta";
$typeName = "Theta";
$helperMethod = "T";
include(DOCS_RESOURCES."/option-indicators/automatic-indicator.php"); 
?>

<p>The follow table describes the arguments that the <code>T</code> method accepts in addition to the <a href='/docs/v2/writing-algorithms/securities/asset-classes/equity-options/greeks-and-implied-volatility/option-indicators#02-Parameters'>standard parameters</a>:</p>

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
                The Option pricing model to use to estimate the IV when calculating theta. 
                If you don't provide a value, the default value is to match the <code class="csharp">optionModel</code><code class="python">option_model</code> parameter.
            </td>
            <td><code class="csharp">null</code><code class="python">None</code></td>
        </tr>
    </tbody>
</table>

<p>For more information about the <code>T</code> method, see <a href='/docs/v2/writing-algorithms/indicators/supported-indicators/theta#02-Using-T-Indicator'>Using T Indicator</a>.</p>

<h4>Manual Indicators</h4>
<?
$name = "theta";
$typeName = "Theta";
$indicatorPage = "theta";
include(DOCS_RESOURCES."/option-indicators/manual-indicator.php");
?>

<h4>Volatility Smoothing</h4>
<?
$typeName = "theta";
include(DOCS_RESOURCES."/option-indicators/iv-smoothing.php");
?>
