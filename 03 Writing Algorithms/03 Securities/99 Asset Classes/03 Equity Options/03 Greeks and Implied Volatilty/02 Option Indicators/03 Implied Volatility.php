<p>Implied Volatility, <script type="math/tex">\sigma</script>, is often interpreted as the market's expectation for the future volatility of a stock and is implied by the price of the stock's options. It is not observable in the market but can be derived from the price of an option.</p>

<h4>Automatic Indicator</h4>
<?
$typeName = "ImpliedVolatility";
$helperMethod = "IV";
include(DOCS_RESOURCES."/option-indicators/automatic-indicator.php"); 
?>

<p>Note that the <code>IV</code> method has extra arguments.</p>

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
            <td><code>period</code></td>
            <td><code>int</code></td>
            <td>The number of periods used to calculate the historical volatility for comparison.</td>
            <td>252</td>
        </tr>
    </tbody>
</table>

<h4>Manual Indicator</h4>
<?
$typeName = "ImpliedVolatility";
$indicatorPage = "implied-volatility";
include(DOCS_RESOURCES."/option-indicators/manual-indicator.php");
?>