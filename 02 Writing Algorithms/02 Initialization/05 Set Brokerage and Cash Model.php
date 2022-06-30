<p>We model your algorithm with margin modeling by default, but you can select a cash account type. Cash accounts don't allow leveraged trading, whereas Margin accounts can support leverage on your account value. To set your brokerage and account type, call the <code>SetBrokerageModel</code> method. For more information about each brokerage and the account types they support, see the <a href="/docs/v2/our-platform/live-trading/brokerages">brokerage integration</a> documentation. For more information about the reality models that the brokerage models set, see <a href="/docs/v2/writing-algorithms/reality-modeling/brokerages">Brokerages</a>.<br></p>


<div class="section-example-container">
	<pre class="csharp">SetBrokerageModel(BrokerageName.InteractiveBrokersBrokerage, AccountType.Margin);</pre>
	<pre class="python">self.SetBrokerageModel(BrokerageName.InteractiveBrokersBrokerage, AccountType.Cash)</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/enumerations/account_type.html"); ?>
