<p>
    Delta, <script type="math/tex">\Delta</script>, is the rate of change of the Option price with respect to the price of the underlying asset. 
    It measures the first-order sensitivity of the price to a movement in underlying price. 
    For example, an Option delta of 0.4 means that if the underlying asset moves by 1%, then the value of the Option moves by 0.4 <script type="math/tex">\times</script> 1% = 0.4%.
    For more information about delta, see <a href='/learning/articles/introduction-to-options/the-greek-letters#delta'>Delta</a>.
</p>

<h4>Automatic Indicators</h4>
<?
// Tag: div section-example-container testable
$name = "delta";
$typeName = "Delta";
$helperMethod = "D";
include(DOCS_RESOURCES."/option-indicators/index.php");
include(DOCS_RESOURCES."/option-indicators/automatic-indicator.php"); 
?>
<div class="regression-test-results">
    <script class="csharp-result" type="text"></script>
    <script class="python-result" type="text"></script>
</div>

<p>The follow table describes the arguments that the <code class='csharp'>D</code><code class='python'>d</code> method accepts in addition to the <a href='/docs/v2/writing-algorithms/securities/asset-classes/index-options/greeks-and-implied-volatility/indicators#02-Parameters'>standard parameters</a>:</p>

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
                The Option pricing model to use to estimate the IV when calculating Delta.
                If you don't provide a value, the default value is to match the <code class="csharp">optionModel</code><code class="python">option_model</code> parameter.
            </td>
            <td><code class="csharp">null</code><code class="python">None</code></td>
        </tr>
    </tbody>
</table>

<p>For more information about the <code class='csharp'>D</code><code class='python'>d</code> method, see <a href='/docs/v2/writing-algorithms/indicators/supported-indicators/delta#02-Using-D-Indicator'>Using D Indicator</a>.</p>

<h4>Manual Indicators</h4>
<?
// Tag: div section-example-container testable
$name = "delta";
$typeName = "Delta";
$indicatorPage = "delta";
include(DOCS_RESOURCES."/option-indicators/index.php");
include(DOCS_RESOURCES."/option-indicators/manual-indicator.php");
?>
<div class="regression-test-results">
    <script class="csharp-result" type="text"></script>
    <script class="python-result" type="text"></script>
</div>

<h4>Volatility Smoothing</h4>
<?
$typeName = "delta";
$assetClass = "Index";
$ivLink = "/docs/v2/writing-algorithms/securities/asset-classes/index-options/greeks-and-implied-volatility/indicators#02-Parameters";
include(DOCS_RESOURCES."/option-indicators/iv-smoothing.php");
?>
