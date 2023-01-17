<p>The <code>KrakenBrokerageModel</code> uses the <code>CashBuyingPowerModel</code> for cash accounts and the <code>SecurityMarginModel</code> for margin accounts.</p>

<p>If you have a margin account, the <code>KrakenBrokerageModel</code> allows 1x leverage for most Crypto pairs. The following table shows pairs that have additional leverage available:</p>

<?php echo file_get_contents(DOCS_RESOURCES."/brokerages/kraken-buying-power.html"); ?>