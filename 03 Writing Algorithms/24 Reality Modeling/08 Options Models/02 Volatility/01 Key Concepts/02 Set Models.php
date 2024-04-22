<p>To set the volatility model of the underlying security of an Option, set the <code>VolatilityModel</code> property of the <code>Security</code> object. The volatility model can have a different resolution than the underlying asset subscription.</p>

<div class="section-example-container">
    <pre class="csharp">// In Initialize
var underlyingSecurity= AddEquity("SPY");
underlyingSecurity.VolatilityModel = new StandardDeviationOfReturnsVolatilityModel(30);</pre>
    <pre class="python"># In Initialize
underlying_security = self.add_equity("SPY")
underlying_security.volatility_model = StandardDeviationOfReturnsVolatilityModel(30)</pre>
</div>


<p>You can also set the volatility model in a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>. If your algorithm has a universe of underlying assets, use the security initializer technique. In order to initialize single security subscriptions with the security initializer, call <code class="csharp">SetSecurityInitializer</code><code class="python">set_security_initializer</code> before you create the subscriptions.</p>

<?
$overwriteCodePy = "if security.Type == SecurityType.Equity:
            security.VolatilityModel = StandardDeviationOfReturnsVolatilityModel(30)";
$overwriteCodeC = "if (security.Type == SecurityType.Equity)
        {
            security.VolatilityModel = new StandardDeviationOfReturnsVolatilityModel(30);
        }";
$comment = "the volatility model";
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>

<p>To view all the pre-built volatility models, see <a href="/docs/v2/writing-algorithms/reality-modeling/options-models/volatility/supported-models">Supported Models</a>.</p>
