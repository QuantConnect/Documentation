<p>The brokerage model of your algorithm automatically sets the slippage model for each security, but you can override it. To manually set the slippage model of a security, call the <code class="csharp">SetSlippageModel</code><code class="python">set_slippage_model</code> method on the <code>Security</code> object.</p>

<div class="section-example-container">
    <pre class="csharp">// In Initialize
var security = AddEquity("SPY");
security.SetSlippageModel(new VolumeShareSlippageModel());</pre>
    <pre class="python"># In Initialize
security = self.add_equity("SPY")
security.set_slippage_model(VolumeShareSlippageModel())</pre>
</div>

<p>You can also set the slippage model in a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>. If your algorithm has a dynamic universe, use the security initializer technique. In order to initialize single security subscriptions with the security initializer, call <code class="csharp">SetSecurityInitializer</code><code class="python">set_security_initializer</code> before you create the subscriptions.</p>

<?
$overwriteCodePy = "security.set_slippage_model(VolumeShareSlippageModel())";
$overwriteCodeC = "security.SetSlippageModel(new VolumeShareSlippageModel());"; 
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>

<p>To view all the pre-built slippage models, see <a href="/docs/v2/writing-algorithms/reality-modeling/slippage/supported-models">Supported Models</a>.</p>
