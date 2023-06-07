<p>In live trading, orders can pass the validations associated with buying power in LEAN and let your brokerage decide to accept or reject them. To disable the validations of the <a href='/docs/v2/writing-algorithms/reality-modeling/buying-power#08-Default-Behavior'>default buying power model</a>, use the <code>NullBuyingPowerModel</code>.</p>

<p>The <a href='/docs/v2/writing-algorithms/reality-modeling/buying-power#08-Default-Behavior'>default position group buying power models</a> are helpful for option trading strategies. However, it can be counterproductive if the option strategy is not supported. To disable the validations of the <a href='/docs/v2/writing-algorithms/reality-modeling/buying-power#08-Default-Behavior'>default position group buying power model</a>, use the <code>NullSecurityPositionGroupModel</code>.

<p>To set the <code>NullBuyingPowerModel</code> for a security subscription, call the <code>SetBuyingPowerModel</code> method with the <code>BuyingPowerModel.Null</code> argument. To set the <code>NullSecurityPositionGroupModel</code> for the portfolio, call the <code>SetPositions</code> with the <code>SecurityPositionGroupModel.Null</code> argument.</p>
<div class="section-example-container">
<pre class="csharp">// In Initialize
Portfolio.SetPositions(SecurityPositionGroupModel.Null);
var equity = AddEquity("SPY");
equity.SetBuyingPowerModel(BuyingPowerModel.Null);</pre>
<pre class="python"># In Initialize
self.Portfolio.SetPositions(SecurityPositionGroupModel.Null)
equity = self.AddEquity("SPY")
equity.SetBuyingPowerModel(BuyingPowerModel.Null)</pre>
</div>
<p>You can call the <code>SetMarginModel</code> method with the <code>SecurityMarginModel.Null</code> argument to achive the same result.<br></p>

<p>You can also set the asset <code>NullBuyingPowerModel</code> in a security initializer. In order to set the buying power of securities in the security initializer, call <code>SetSecurityInitializer</code> before you create security subscriptions and after you call <code>SetBrokerageModel</code>.<br></p>

<?
$overwriteCodePy = "security.SetBuyingPowerModel(BuyingPowerModel.Null)";
$overwriteCodeC = "security.SetBuyingPowerModel(BuyingPowerModel.Null);";
$comment = "the security buying power";
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>