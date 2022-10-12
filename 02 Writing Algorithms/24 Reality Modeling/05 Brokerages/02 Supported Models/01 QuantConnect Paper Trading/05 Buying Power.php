<p>The <code>DefaultBrokerageModel</code> uses sets the buying power model based on the asset class of the security. The following table shows the default buying power model of each asset class:</p>

<?php echo file_get_contents(DOCS_RESOURCES."/brokerages/default-buying-power-models.html"); ?>

<p>If you have a margin account, the <code>DefaultBrokerageModel</code> allows 2x leverage for Equities, 50x leverage for Forex and CFDs, and 1x leverage for the remaining asset classes.</p>
