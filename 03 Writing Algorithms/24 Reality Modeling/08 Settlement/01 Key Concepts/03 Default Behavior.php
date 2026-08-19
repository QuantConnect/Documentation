<p>The brokerage model of your algorithm automatically sets the settlement model for each security. The default brokerage model is the <code>DefaultBrokerageModel</code>, which sets the settlement model based on the security type and your account type. The following table shows how it sets the settlement models:</p>

<? 
$brokerageModelName = "DefaultBrokerageModel";
$includeLinks = false;
include(DOCS_RESOURCES."/reality-modeling/default-settlement-models.php"); 
?>


<p>The default delayed settlement rule for US Equity and Option trades is T+1 at 6 AM Eastern Time (ET), where T+1 counts trading days. For example, if you sell on Monday, the trade settles on Tuesday at 6 AM. This delay only applies to cash accounts. Future contracts instead settle their profit and loss daily.</p>
