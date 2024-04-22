<p>The buying power model sets the leverage for each security in your algorithm, but you can override its leverage settings after the buying power model is set.</p>

<p>To set the leverage when you create a security subscription, pass in a <code>leverage</code> argument.</p>
<div class="section-example-container">
<pre class="csharp">// In Initialize
AddEquity("SPY", leverage: 3);</pre>
<pre class="python"># In Initialize
AddEquity("SPY", leverage=3)</pre>
</div>

<p>You can also set the asset leverage in a security initializer. In order to set the leverage of securities in the security initializer, call <code class="csharp">SetSecurityInitializer</code><code class="python">set_security_initializer</code> before you create security subscriptions and before you call <code class="csharp">SetBrokerageModel</code><code class="python">set_brokerage_model</code>. If you pass in a <code>leverage</code> argument when you create the security subscription, the <code>leverage</code> argument takes precedence over the <code>SetLeverage</code> call in the security initializer.<br></p>


<?php

$overwriteCodePy = "security.SetLeverage(3)";
$overwriteCodeC = "security.SetLeverage(3);";
$comment = "the security leverage";
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>


<p>To set the leverage for all securities in a universe, set the <code>UniverseSettings.Leverage</code> property.</p>

<div class="section-example-container">
	<pre class="csharp">// In Initialize
UniverseSettings.Leverage = 3;</pre>
	<pre class="python"># In Initialize
self.universe_settings.leverage = 3</pre>
</div>

<p>In live trading, LEAN doesn't ignore the leverage you set. However, if you set a different leverage from what your brokerage provides, it creates a mismatch between the buying power in your algorithm and the buying power the brokerage gives you. In this case, orders can pass the validations in LEAN but your brokerage may reject them.</p>
