<p>The brokerage model of your algorithm automatically sets the fill model for each security, but you can override it. To manually set the fill model of a security, call the <code>SetFillModel</code> method on the Security object.</p>
<div class="section-example-container">
    <pre class="csharp">// In Initialize
var security = AddEquity("SPY");
security.SetFillModel(new ImmediateFillModel());</pre>
    <pre class="python"># In Initialize
security = self.add_equity("SPY")
security.set_fill_model(ImmediateFillModel())</pre>
</div>

<p>You can also set the fill model in a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>. If your algorithm has a dynamic universe, use the security initializer technique. In order to initialize single security subscriptions with the security initializer, call <code class="csharp">SetSecurityInitializer</code><code class="python">set_security_initializer</code> before you create the subscriptions.</p><p>

<?
$overwriteCodePy = "security.SetFillModel(ImmediateFillModel())";
$overwriteCodeC = "security.SetFillModel(new ImmediateFillModel());";
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>

<p>To view all the pre-built fill models, see <a href='/docs/v2/writing-algorithms/reality-modeling/trade-fills/supported-models'>Supported Models</a>.</p>
