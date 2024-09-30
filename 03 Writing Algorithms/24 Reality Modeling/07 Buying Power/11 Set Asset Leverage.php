<p>The buying power model sets the leverage for each security in your algorithm, but you can override its leverage settings after the buying power model is set.</p>

<p>To set the leverage when you create a security subscription, pass in a <code>leverage</code> argument.</p>
<div class="section-example-container">
<pre class="csharp">public override void Initialize()
{
	// Set the leverage to 3x manually subjected to specific need
	AddEquity("SPY", leverage: 3);
}</pre>
<pre class="python">def initialize(self) -&gt; None:
	# Set the leverage to 3x manually subjected to specific need
	self.add_equity("SPY", leverage=3)</pre>
</div>

<p>You can also set the asset leverage in a security initializer. In order to set the leverage of securities in the security initializer, call <code class="csharp">SetSecurityInitializer</code><code class="python">set_security_initializer</code> before you create security subscriptions and before you call <code class="csharp">SetBrokerageModel</code><code class="python">set_brokerage_model</code>. If you pass in a <code>leverage</code> argument when you create the security subscription, the <code>leverage</code> argument takes precedence over the <code class="csharp">SetLeverage</code><code class="python">set_leverage</code> call in the security initializer.<br></p>


<?php

$overwriteCodePy = "security.set_leverage(3)";
$overwriteCodeC = "security.SetLeverage(3);";
$comment = "the security leverage";
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>


<p>To set the leverage for all securities in a universe, set the <code class="csharp">UniverseSettings.Leverage</code><code class="python">universe_settings.leverage</code> property.</p>

<div class="section-example-container">
	<pre class="csharp">public override void Initialize()
{
	// Set the leverage to 3x to all securities, it should only be used for a very narrow spectrum of security universe with the same leverage
	// E.g. US Equities universe composed of only primary stocks in NYSE exchange
	UniverseSettings.Leverage = 3;
}</pre>
	<pre class="python">def initialize(self) -&gt;None:
	# Set the leverage to 3x to all securities, it should only be used for a very narrow spectrum of security universe with the same leverage
	# E.g. US Equities universe composed of only primary stocks in NYSE exchange
	self.universe_settings.leverage = 3</pre>
</div>

<p>In live trading, LEAN doesn't ignore the leverage you set. However, if you set a different leverage from what your brokerage provides, it creates a mismatch between the buying power in your algorithm and the buying power the brokerage gives you. In this case, orders can pass the validations in LEAN but your brokerage may reject them.</p>
