<p>Algorithms can elect to disable order margin checks, to let the brokerage decide to accept or reject the trades. This is helpful 
  in live trading where you might have a more permissive brokerage margin allowance unsupported by QuantConnect.
  To disable the validations of the <a href='/docs/v2/writing-algorithms/reality-modeling/buying-power#08-Default-Behavior'>default buying power model</a>, use the <code>NullBuyingPowerModel</code>.</p>

<p>The <a href='/docs/v2/writing-algorithms/reality-modeling/buying-power#08-Default-Behavior'>default position group buying power models</a> are helpful for Option trading strategies. However, it can be counterproductive if it's not a <a href='/docs/v2/writing-algorithms/trading-and-orders/option-strategies'>supported Option strategy</a>. To disable the validations of the default position group buying power model, use the <code>NullSecurityPositionGroupModel</code>.</p>

<p>To set the <code>NullBuyingPowerModel</code> for a security subscription, call the <code>SetBuyingPowerModel</code> method with the <code>BuyingPowerModel.Null</code> argument. To set the <code>NullSecurityPositionGroupModel</code> for the portfolio, call the <code>SetPositions</code> method with the <code>SecurityPositionGroupModel.Null</code> argument.</p>
<div class="section-example-container">
<pre class="csharp">// In Initialize
Portfolio.SetPositions(SecurityPositionGroupModel.Null);

var equity = AddEquity("SPY");
equity.SetBuyingPowerModel(BuyingPowerModel.Null);
// Alias: 
// equity.SetMarginModel(SecurityMarginModel.Null);</pre>
<pre class="python"># In Initialize
self.Portfolio.SetPositions(SecurityPositionGroupModel.Null)

equity = self.AddEquity("SPY")
equity.SetBuyingPowerModel(BuyingPowerModel.Null)
# Alias:
# equity.SetMarginModel(SecurityMarginModel.Null)</pre>
</div>

<p>You can also set the asset <code>NullBuyingPowerModel</code> in a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>. If your algorithm has a universe, use the security initializer technique. In order to set the buying power of securities in the security initializer, call <code>SetSecurityInitializer</code> before you create security subscriptions and after you call <code>SetBrokerageModel</code>.<br></p>

<?
$overwriteCodePy = "security.SetBuyingPowerModel(BuyingPowerModel.Null)";
$overwriteCodeC = "security.SetBuyingPowerModel(BuyingPowerModel.Null);";
$comment = "the security buying power";
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>
