<p>To set a data filter for a security, call the <code>SetDataFilter</code> property on the <code>Security</code> object.</p>

<div class="section-example-container">
    <pre class="csharp">// In Initialize
var spy = AddEquity("SPY");
spy.SetDataFilter(new SecurityDataFilter());</pre>
    <pre class="python"># In Initialize
spy = AddEquity("SPY")
spy.SetDataFilter(SecurityDataFilter())</pre>
</div>

<p>You can also set the data filter model in a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>. If your algorithm has a universe, use the security initializer technique. In order to initialize single security subscriptions with the security initializer, call <code>SetSecurityInitializer</code> before you create the subscriptions.</p>

<?php
$overwriteCodePy = "security.SetDataFilter(SecurityDataFilter())";
$overwriteCodeC = "security.SetDataFilter(new SecurityDataFilter());";
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>