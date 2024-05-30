<p>The brokerage model of your algorithm automatically sets the buying power model for each security, but you can override it. To manually set the buying power model of a security, call the <code class="csharp">SetBuyingPowerModel</code><code class="python">set_buying_power_model</code> method on the <code>Security</code> object.</p>

<div class="section-example-container">
    <pre class="csharp">// In Initialize
var security = AddEquity("SPY");
security.SetBuyingPowerModel(new SecurityMarginModel(3m));</pre>
    <pre class="python"># In Initialize
security = self.add_equity("SPY")
security.set_buying_power_model(SecurityMarginModel(3))</pre>
</div>

<p>You can also set the buying power model in a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>. If your algorithm has a universe, use the security initializer technique. In order to initialize single security subscriptions with the security initializer, call <code class="csharp">SetSecurityInitializer</code><code class="python">set_security_initializer</code> before you create the subscriptions.</p>

<?php
$overwriteCodePy = "security.set_buying_power_model(SecurityMarginModel(3))";
$overwriteCodeC = "security.SetBuyingPowerModel(new SecurityMarginModel(3m));";
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>

<p>You cannot change the position group buying power models.</p>
