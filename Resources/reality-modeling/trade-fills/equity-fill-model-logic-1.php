<p>The fill logic of <?php echo $orderType; ?> orders depends on the data format of the security subscription and the order direction. <?php if ($followingTable)
{
	echo "The following table shows the fill price of {$orderType} orders given these factors. ";
} 
?>To determine the fill price of the order, the fill model first checks the most recent tick for the security. If your security subscription doesn't provide tick data, the fill model checks the most recent <code>QuoteBar</code>. If your security subscription doesn't provide quote data, the fill model checks the most recent <code>TradeBar</code>.</p>