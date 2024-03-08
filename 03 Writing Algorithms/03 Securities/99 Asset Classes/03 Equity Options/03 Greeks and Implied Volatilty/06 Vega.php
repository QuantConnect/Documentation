<p>The Vega, <script type="math/tex">\nu</script>, is the rate of change in the value of the option with respect to the volatility of the underlying asset.</p>

<h4>Automatic Indicator</h4>
<?
$typeName = "Vega";
$helperMethod = "V";
include(DOCS_RESOURCES."/option-indicators/automatic-indicator.php"); 
?>

<p>Note that the <code>V</code> method has an extra argument.</p>

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
$typeName = "Vega";
$indicatorPage = "vega";
include(DOCS_RESOURCES."/option-indicators/manual-indicator.php");
?>