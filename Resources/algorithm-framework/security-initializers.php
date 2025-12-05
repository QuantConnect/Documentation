<p>
Instead of configuring global universe settings, you can individually configure the settings of each security in the universe with a security initializer. Security initializers let you apply any <a href='/docs/v2/writing-algorithms/reality-modeling/key-concepts#02-Security-Level-Models'>security-level reality model</a> or special data requests on a per-security basis. To set the security initializer, in the <code class="csharp">Initialize</code><code class="python">initialize</code> method, call the <code class="csharp">SetSecurityInitializer</code><code class="python">set_security_initializer</code> method and then define the security initializer.</p>
</p>
<div class="section-example-container">
<pre class="csharp">// A custom security initializer can override default models such as 
// setting new fee and fill models for the security.
SetSecurityInitializer(CustomSecurityInitializer);

private void CustomSecurityInitializer(Security security)
{
    security.SetFeeModel(new ConstantFeeModel(0, "USD"));
}
</pre>
<pre class="python"># A custom security initializer can override default models such as
# setting new fee and fill models for the security.
self.set_security_initializer(self._custom_security_initializer)

def _custom_security_initializer(self, security: Security) -&gt; None:
    security.set_fee_model(ConstantFeeModel(0, "USD"))
</pre>
</div>

<p>For simple requests, you can use the functional implementation of the security initializer. This style lets you configure the security object with one line of code.</p>
<div class="section-example-container">
<pre class="csharp">// Disable the trading fees for each security by passing a functional 
// implementation for the SetSecurityInitializer argument.
SetSecurityInitializer(security =&gt; security.SetFeeModel(new ConstantFeeModel(0, "USD")));</pre>
<pre class="python"># Disable the trading fees for each security by using lambda function 
# for the set_security_initializer argument.
self.set_security_initializer(lambda security: security.set_fee_model(ConstantFeeModel(0, "USD")))</pre>
</div>

<? include(DOCS_RESOURCES."/reality-modeling/security-initializers.html");?>

<p>The default security initializer also sets the leverage of each security and intializes each security with a seeder function. To extend upon the default security initializer instead of overwriting it, call <code class="csharp">AddSecurityInitializer</code><code class="python">add_security_initializer</code> before you create the subscriptions.</p>

<?
$overwriteCodePy = "security.set_fee_model(ConstantFeeModel(0, \"USD\"))";
$overwriteCodeC = "security.SetFeeModel(new ConstantFeeModel(0, \"USD\"));";
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>