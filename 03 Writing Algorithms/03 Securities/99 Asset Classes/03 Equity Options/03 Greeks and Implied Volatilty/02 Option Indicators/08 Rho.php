<p>Rho, <script type="math/tex">\rho</script>, is the rate of change of the value of a derivative with respect to the interest rate. It is usually small and not a big issue in practice unless the option is deep in-the-money and has a long horizon. The interest rate would matter because we need to discount a larger cash flow over a longer horizon. Rho for the European options:</p>

<h4>Automatic Indicator</h4>
<?
$typeName = "Rho";
$helperMethod = "R";
include(DOCS_RESOURCES."/option-indicators/automatic-indicator.php"); 
?>

<p>Note that the <code>R</code> method has an extra argument.</p>

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
$typeName = "Rho";
$indicatorPage = "rho";
include(DOCS_RESOURCES."/option-indicators/manual-indicator.php");
?>