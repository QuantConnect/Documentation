<p>We model your algorithm with margin modeling by default, but you can select a cash account type. Cash accounts don't allow leveraged trading, whereas Margin accounts can support leverage on your account value. To set your brokerage and account type, call the <code>SetBrokerageModel</code> method. For more information about each brokerage and the account types they support, see the <a href="/docs/v2/cloud-platform/live-trading/brokerages">brokerage integration</a> documentation. For more information about the reality models that the brokerage models set, see <a href="/docs/v2/writing-algorithms/reality-modeling/brokerages/supported-models">Supported Models</a>.<br></p>
<div class="section-example-container">
	<pre class="csharp">SetBrokerageModel(BrokerageName.InteractiveBrokersBrokerage, AccountType.Margin);</pre>
	<pre class="python">self.set_brokerage_model(BrokerageName.INTERACTIVE_BROKERS_BROKERAGE, AccountType.CASH)</pre>
</div>

<p>The <code>AccountType</code> enumeration has the following members:</p>

<div data-tree="QuantConnect.AccountType"></div>