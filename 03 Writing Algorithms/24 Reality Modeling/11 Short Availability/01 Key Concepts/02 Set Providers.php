<p>The brokerage model of your algorithm automatically sets the settlement model for each security, but you can override it. To manually set the shortable provider of a security, call the <code>SetShortableProvider</code> method on the <code>Security</code> object.</p>


<div class="section-example-container">
    <pre class="csharp" style="">// In Initialize
var security = AddEquity("SPY");
security.SetShortableProvider(new AtreyuShortableProvider(SecurityType.Equity, Market.USA));</pre>
    <pre class="python"># In Initialize
security = self.AddEquity("SPY")
security.SetShortableProvider(AtreyuShortableProvider(SecurityType.Equity, Market.USA))</pre>
</div>

<p>You can also set the shortable provider in a security initializer. If your algorithm has a universe, use the security initializer technique. In order to initialize single security subscriptions with the security initializer, call <code>SetSecurityInitializer</code> before you create the subscriptions.</p>

<div class="section-example-container">
<pre class="csharp" style="">// In Initialize
SetSecurityInitializer(CustomSecurityInitializer);
AddEquity("SPY");

private void CustomSecurityInitializer(Security security)
{
    security.SetShortableProvider(new AtreyuShortableProvider(SecurityType.Equity, Market.USA));
}
</pre>
<pre class="python"># In Initialize
self.SetSecurityInitializer(self.CustomSecurityInitializer)
self.AddEquity("SPY")

def CustomSecurityInitializer(self, security: Security) -&gt; None:
    security.SetShortableProvider(AtreyuShortableProvider(SecurityType.Equity, Market.USA))
</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/reality-modeling/security-initializers.html");?>

<p>To extend upon the default security initializer instead of overwriting it, create a custom <code>BrokerageModelSecurityInitializer</code>.</p>

<?php
include(DOCS_RESOURCES."/reality-modeling/brokerage-mondel-security-init.php");
$overwriteCodePy = "security.SetShortableProvider(AtreyuShortableProvider(SecurityType.Equity, Market.USA))";
$overwriteCodeC = "security.SetShortableProvider(new AtreyuShortableProvider(SecurityType.Equity, Market.USA));";
$getBrokerageModelInitCodeBlock($overwriteCodePy, $overwriteCodeC);
?>

<p>To view all the pre-built shortable providers, see <a href='/docs/v2/writing-algorithms/reality-modeling/short-availability/supported-providers'>Supported Providers</a>.</p>