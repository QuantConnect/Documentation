<p>The brokerage model of your algorithm automatically sets the settlement model for each security, but you can override it. To manually set the settlement model of a security, set the <code>SettlementModel</code> property on the <code>Security</code> object.</p>
<div class="section-example-container">
    <pre class="csharp">// In Initialize
var security = AddEquity("SPY");
// Set a delayed settlement model that settles 7 days after the trade at 8 AM
security.SettlementModel = new DelayedSettlementModel(7, TimeSpan.FromHours(8));</pre>
    <pre class="python"># In Initialize
security = self.AddEquity("SPY")
# Set a delayed settlement model that settles 7 days after the trade at 8 AM
security.SettlementModel = DelayedSettlementModel(7, timedelta(hours=8))</pre>
</div>

<p>You can also set the settlement model in a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>. If your algorithm has a universe, use the security initializer technique. In order to initialize single security subscriptions with the security initializer, call <code>SetSecurityInitializer</code> before you create the subscriptions.</p>

<?php
include(DOCS_RESOURCES."/reality-modeling/brokerage-mondel-security-init.php");
$overwriteCodePy = "security.SettlementModel = DelayedSettlementModel(7, timedelta(hours=8))";
$overwriteCodeC = "security.SettlementModel = new DelayedSettlementModel(7, TimeSpan.FromHours(8));";
$getBrokerageModelInitCodeBlock($overwriteCodePy, $overwriteCodeC);
?>

<p>To view all the pre-built settlement models, see <a href="/docs/v2/writing-algorithms/reality-modeling/settlement/supported-models">Supported Models</a>.</p>
