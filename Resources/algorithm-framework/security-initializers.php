<p>
Instead of configuring global universe settings, you can individually configure the settings of each security in the universe with a security initializer. Security initializers let you apply any <a href='/docs/v2/writing-algorithms/reality-modeling/key-concepts#02-Security-Level-Models'>security-level reality model</a> or special data requests on a per-security basis. To set the security initializer, in the <code>Initialize</code> method, call the <code>SetSecurityInitializer</code> method and then define the security initializer.</p>
</p>
<div class="section-example-container">
<pre class="csharp">//In Initialize
SetSecurityInitializer(CustomSecurityInitializer);

private void CustomSecurityInitializer(Security security)
{
    // Disable trading fees
    security.SetFeeModel(new ConstantFeeModel(0, "USD"));
}
</pre>
<pre class="python">#In Initialize
self.SetSecurityInitializer(self.CustomSecurityInitializer)

def CustomSecurityInitializer(self, security: Security) -&gt; None:
    # Disable trading fees
    security.SetFeeModel(ConstantFeeModel(0, "USD"))
</pre>
</div>

<p>For simple requests, you can use the functional implementation of the security initializer. This style lets you configure the security object with one line of code.</p>
<div class="section-example-container">
<pre class="csharp">SetSecurityInitializer(security =&gt; security.SetFeeModel(new ConstantFeeModel(0, "USD")));</pre>
<pre class="python">self.SetSecurityInitializer(lambda security: security.SetFeeModel(ConstantFeeModel(0, "USD")))</pre>
</div>

<p>In some cases, you may want to trade a security in the same time loop that you create the security subscription. To avoid errors, use a security initializer to set the market price of each security to the last known price.</p>
<div class="section-example-container">
<pre class="csharp">var seeder = new FuncSecuritySeeder(GetLastKnownPrices);
SetSecurityInitializer(security =&gt; seeder.SeedSecurity(security));</pre>
<pre class="python">seeder = FuncSecuritySeeder(self.GetLastKnownPrices)
self.SetSecurityInitializer(lambda security: seeder.SeedSecurity(security))</pre>
</div>

<p>The <code>GetLastKnownPrices</code> method seeds the security price by first gathering the last five points of data at the security resolution. For example, if your security subscription is for minute resolution data, the method first gathers data from the last 5 minutes for the security. If there is no data during this period, the <code>GetLastKnownPrices</code> method gathers data over the last three days. If there is no data during either period, the security price remains at 0.</p>

<? include(DOCS_RESOURCES."/reality-modeling/security-initializers.html");?>

<p>The default security initializer also sets the leverage of each security and intializes each security with a seeder function. To extend upon the default security initializer instead of overwriting it, create a custom <code>BrokerageModelSecurityInitializer</code>.</p>

<?
$overwriteCodePy = "security.SetFeeModel(ConstantFeeModel(0, \"USD\"))";
$overwriteCodeC = "security.SetFeeModel(new ConstantFeeModel(0, \"USD\"));";
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>

<p>To set a seeder function without overwriting the reality models of the brokerages, use the standard <code>BrokerageModelSecurityInitializer</code>.</p>
<div class="section-example-container">
<pre class="csharp">SetSecurityInitializer(new BrokerageModelSecurityInitializer(BrokerageModel, new FuncSecuritySeeder(GetLastKnownPrices)));
</pre>
<pre class="python">self.SetSecurityInitializer(BrokerageModelSecurityInitializer(self.BrokerageModel, FuncSecuritySeeder(self.GetLastKnownPrices)))
</pre>
</div>
