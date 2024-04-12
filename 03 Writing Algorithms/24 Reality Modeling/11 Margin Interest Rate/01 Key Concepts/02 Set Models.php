<p>The brokerage model of your algorithm automatically sets the margin interest rate model for each security, but you can override it. To manually set the margin interest rate model of a security, assign a model to the <code>MarginInterestRateModel</code> property of the Security object.</p>
<div class="section-example-container">
    <pre class="csharp">// In Initialize
var security = AddEquity("SPY");
security.MarginInterestRateModel = MarginInterestRateModel.Null;</pre>
    <pre class="python"># In Initialize
security = self.add_equity("SPY")
security.set_margin_interest_rate_model(MarginInterestRateModel.NULL)</pre>
</div>

<p>You can also set the margin interest rate model in a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>. If your algorithm has a dynamic universe, use the security initializer technique. In order to initialize single security subscriptions with the security initializer, call <code>SetSecurityInitializer</code> before you create the subscriptions.</p><p>

<?
$overwriteCodePy = "security.SetMarginInterestRateModel(MarginInterestRateModel.Null)";
$overwriteCodeC = "security.MarginInterestRateModel = MarginInterestRateModel.Null;";
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>

<p>To view all the pre-built margin interest rate models, see <a href='/docs/v2/writing-algorithms/reality-modeling/margin-interest-rate/supported-models'>Supported Models</a>.</p>
