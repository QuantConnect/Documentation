<p>Gamma, <script type="math/tex">\Gamma</script>, is the rate of change of the portfolio's delta with respect to the underlying asset's price. It represents the second-order sensitivity of the option to a movement in the underlying asset's price.</p>

<h4>Automatic Indicator</h4>
<?
$typeName = "Gamma";
$helperMethod = "G";
include(DOCS_RESOURCES."/option-indicators/automatic-indicator.php"); 
?>

<p>Note that the <code>G</code> method has an extra argument.</p>

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
            <td>The option pricing model used to estimate the IV for Greek calculation. Will use the <code>optionModel</code> if not specified.</td>
            <td><code class="csharp">null</code><code class="python">None</code></td>
        </tr>
    </tbody>
</table>

<h4>Manual Indicator</h4>
<?
$typeName = "Gamma";
$indicatorPage = "gamma";
include(DOCS_RESOURCES."/option-indicators/manual-indicator.php");
?>