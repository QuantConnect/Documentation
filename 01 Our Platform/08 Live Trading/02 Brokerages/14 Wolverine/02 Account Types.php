<p>Wolverine supports cash and margin accounts.</p>

<?php echo file_get_contents(DOCS_RESOURCES."/brokerages/set-brokerage-model/wolverine.html"); ?>

<h4>Create an Account</h4>

<p>To create a Wolverine account, <a href='https://www.tradewex.com/Home/Contact' rel='nofollow' target="_blank">contact their staff</a> through the TradeWex website.</p>

<h4>Paper Trading</h4>
<p>Wolverine doesn't support paper trading, but you can follow these steps to simulate it:</p>

<ol>
    <li>In the <code>Initialize</code> method of your algorithm, add one of the preceding <code>SetBrokerageModel</code> method calls.</li>
    <li><a href="/docs/v2/our-platform/live-trading/brokerages/quantconnect-paper-trading#14-Deploy-Live-Algorithms">Deploy your algorithm with the QuantConnect Paper Trading brokerage</a>.</li>
</ol>
