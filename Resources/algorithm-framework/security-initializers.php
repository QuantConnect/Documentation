<p>
Instead of configuring global universe settings, you can individually configure the settings of each security in the universe with a security initializer. Security initializers let you apply any <a href='/docs/v2/writing-algorithms/reality-modeling/key-concepts#02-Security-Level-Models'>security-level reality model</a> or special data requests on a per-security basis. To set the security initializer, in the <code class="csharp">Initialize</code><code class="python">initialize</code> method, call the <code class="csharp">SetSecurityInitializer</code><code class="python">set_security_initializer</code> method and then define the security initializer.</p>
</p>
<div class="section-example-container">
<pre class="csharp">// A custom security initializer can override default models such as setting default fee and fill models for the security.
SetSecurityInitializer(CustomSecurityInitializer);

private void CustomSecurityInitializer(Security security)
{
    security.SetFeeModel(new ConstantFeeModel(0, "USD"));
}
</pre>
<pre class="python"># A custom security initializer can override default models such as setting default fee and fill models for the security.
self.set_security_initializer(self._custom_security_initializer)

def _custom_security_initializer(self, security: Security) -&gt; None:
    security.set_fee_model(ConstantFeeModel(0, "USD"))
</pre>
</div>

<p>For simple requests, you can use the functional implementation of the security initializer. This style lets you configure the security object with one line of code.</p>
<div class="section-example-container">
<pre class="csharp">// Disable the trading fees for each security by passing a functional implementation for the SetSecurityInitializer argument.
SetSecurityInitializer(security =&gt; security.SetFeeModel(new ConstantFeeModel(0, "USD")));</pre>
<pre class="python"># Disable the trading fees for each security by using lambda function for the SetSecurityInitializer argument.
self.set_security_initializer(lambda security: security.set_fee_model(ConstantFeeModel(0, "USD")))</pre>
</div>

<p>In some cases, you may want to trade a security in the same time loop that you create the security subscription. To avoid errors, use a security initializer to set the market price of each security to the last known price. The <code class="csharp">GetLastKnownPrices</code><code class="python">get_last_known_prices</code> method seeds the security price by gathering the security data over the last 3 days. If there is no data during this period, the security price remains at 0.</p>
<div class="section-example-container">
<pre class="csharp">// Gather the last 3 days security price by using GetLastKnowPrice as seed on initialize.
var seeder = new FuncSecuritySeeder(GetLastKnownPrices);
SetSecurityInitializer(security =&gt; seeder.SeedSecurity(security));</pre>
<pre class="python"># Gather the last 3 days security price by using get_last_known_prices as seed on initialize.
seeder = FuncSecuritySeeder(self.get_last_known_prices)
self.set_security_initializer(lambda security: seeder.seed_security(security))</pre>
</div>

<? include(DOCS_RESOURCES."/reality-modeling/security-initializers.html");?>

<p>The default security initializer also sets the leverage of each security and intializes each security with a seeder function. To extend upon the default security initializer instead of overwriting it, create a custom <code>BrokerageModelSecurityInitializer</code>.</p>

<?
$overwriteCodePy = "security.set_fee_model(ConstantFeeModel(0, \"USD\"))";
$overwriteCodeC = "security.SetFeeModel(new ConstantFeeModel(0, \"USD\"));";
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>

<p>To set a seeder function without overwriting the reality models of the brokerage, use the standard <code>BrokerageModelSecurityInitializer</code>.</p>
<div class="section-example-container">
<pre class="csharp">var seeder = new FuncSecuritySeeder(GetLastKnownPrices);
SetSecurityInitializer(new BrokerageModelSecurityInitializer(BrokerageModel, seeder));
</pre>
<pre class="python">seeder = FuncSecuritySeeder(self.get_last_known_prices)
self.set_security_initializer(BrokerageModelSecurityInitializer(self.brokerage_model, seeder))
</pre>
</div>
