<p>Theta, <script type="math/tex">\Theta</script>, is the rate of change of the value of the option with respect to the passage of time. It is also referred to as the time decay of the portfolio.</p>

<h4>Automatic Indicator</h4>
<?
$typeName = "Theta";
$helperMethod = "T";
include(DOCS_RESOURCES."/option-indicators/automatic-indicator.php"); 
?>

<p>Note that the <code>T</code> method has an extra argument.</p>

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
$typeName = "Theta";
$indicatorPage = "theta";
include(DOCS_RESOURCES."/option-indicators/manual-indicator.php");
?>