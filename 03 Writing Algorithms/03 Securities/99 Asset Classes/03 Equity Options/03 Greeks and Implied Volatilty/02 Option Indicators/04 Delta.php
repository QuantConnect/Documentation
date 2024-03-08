<p>Delta, <script type="math/tex">\Delta</script>, is the rate of change of the option price with respect to the price of the underlying asset. It measures the first-order sensitivity of the price to a movement in stock price S. The option delta is 0.4 means that if the underlying moves by for example 1%, then the value of the option will move by 0.4 × 1%.</p>

<h4>Automatic Indicator</h4>
<?
$typeName = "Delta";
$helperMethod = "D";
include(DOCS_RESOURCES."/option-indicators/automatic-indicator.php"); 
?>

<p>Note that the <code>D</code> method has an extra argument.</p>

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
$typeName = "Delta";
$indicatorPage = "delta";
include(DOCS_RESOURCES."/option-indicators/manual-indicator.php");
?>