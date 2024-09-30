<p>You can disable order margin checks and opt to let your brokerage decide to accept or reject the trades. 
  This is helpful in live trading if you have a more permissive brokerage margin allowance that what LEAN models. 
  The <a href='/docs/v2/writing-algorithms/reality-modeling/buying-power#08-Default-Behavior'>default position group buying power models</a> are helpful for Option trading strategies. 
  However, it can be counterproductive if it's not a <a href='/docs/v2/writing-algorithms/trading-and-orders/option-strategies'>supported Option strategy</a>. 
  To disable the validations of the default position group buying power model, use the <code>NullSecurityPositionGroupModel</code>.
  To set the <code>NullSecurityPositionGroupModel</code> for the portfolio, during <a href='/docs/v2/writing-algorithms/initialization'>initialization</a>, call the <code class="csharp">SetPositions</code><code class="python">set_positions</code> method with the <code>SecurityPositionGroupModel.<span class="csharp">Null</span><span class="python">NULL</span></code> argument.
</p>

<div class="section-example-container">
<pre class="csharp">Portfolio.SetPositions(SecurityPositionGroupModel.Null);</pre>
<pre class="python">self.portfolio.set_positions(SecurityPositionGroupModel.NULL)</pre>
</div>

<p>To disable the validations of the <a href='/docs/v2/writing-algorithms/reality-modeling/buying-power#08-Default-Behavior'>default buying power model</a>, use the <code>NullBuyingPowerModel</code>. To set the <code>NullBuyingPowerModel</code> for a security subscription, call the <code class="csharp">SetBuyingPowerModel</code><code class="python">set_buying_power_model</code> method with the <code>BuyingPowerModel.<span class="csharp">Null</span><span class="python">NULL</span></code> argument.</p>
<div class="section-example-container">
<pre class="csharp">public override void Initialize()
{
    var equity = AddEquity("SPY");
    // To disable any buying power of the selected security
    equity.SetBuyingPowerModel(BuyingPowerModel.Null);
    // Alias: 
    // equity.SetMarginModel(SecurityMarginModel.Null);
}</pre>
<pre class="python">def initialize(self) -&gt; None:
    equity = self.add_equity("SPY")
    # To disable any buying power of the selected security
    equity.set_buying_power_model(BuyingPowerModel.NULL)
    # Alias:
    # equity.set_margin_model(SecurityMarginModel.NULL)</pre>
</div>

<p>
  You can also set the <code>NullBuyingPowerModel</code> in a <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>security initializer</a>. 
  If your algorithm has a universe, use the security initializer technique. 
  To set the buying power of securities in the security initializer, <a href='/docs/v2/writing-algorithms/initialization#05-Set-Brokerage-and-Cash-Model'>set the brokerage model</a>, <a href='/docs/v2/writing-algorithms/initialization#07-Set-Security-Initializer'>set the security initializer</a>, and then create security subscriptions.
</p>

<?
$overwriteCodePy = "security.set_buying_power_model(BuyingPowerModel.NULL)";
$overwriteCodeC = "security.SetBuyingPowerModel(BuyingPowerModel.Null);";
$comment = "the security buying power";
include(DOCS_RESOURCES."/reality-modeling/brokerage-model-security-init.php");
?>
