<p>The brokerage model of your algorithm automatically sets the settlement model for each security, but you can override it. To manually set the settlement model of a security, call the <code>SetSettlementModel</code> method on the <code>Security</code> object.</p>
<div class="section-example-container">
    <pre class="csharp">// In Initialize
var security = AddEquity("SPY");
// Set a delayed settlement model that settles 7 days after the trade at 8 AM
security.SetSettlementModel(new DelayedSettlementModel(7, TimeSpan.FromHours(8)));</pre>
    <pre class="python"># In Initialize
security = self.add_equity("SPY")
# Set a delayed settlement model that settles 7 days after the trade at 8 AM
security.set_settlement_model(DelayedSettlementModel(7, timedelta(hours=8)))</pre>
</div>

<p>You can also set the settlement model in a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>. If your algorithm has a universe, use the security initializer technique. In order to initialize single security subscriptions with the security initializer, call <code class="csharp">SetSecurityInitializer</code><code class="python">set_security_initializer</code> before you create the subscriptions.</p>

<?php
$overwriteCodePy = "security.SetSettlementModel(DelayedSettlementModel(7, timedelta(hours=8)))";
$overwriteCodeC = "security.SetSettlementModel(new DelayedSettlementModel(7, TimeSpan.FromHours(8)));";
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>

<p>To view all the pre-built settlement models, see <a href="/docs/v2/writing-algorithms/reality-modeling/settlement/supported-models">Supported Models</a>.</p>
