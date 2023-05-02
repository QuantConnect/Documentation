<p>The brokerage model of your algorithm automatically sets the settlement model for each security. The default brokerage model is the <code>DefaultBrokerageModel</code>, which sets the settlement model based on the security type and your account type. The following table shows how it sets the settlement models:</p>

<? 
$brokerageModelName = "DefaultBrokerageModel";
include(DOCS_RESOURCES."/reality-modeling/default-settlement-models.php"); 
?>


<p>The default delayed settlement rule for US Equity trades is T+2 at 8 AM Eastern Time (ET). For example, if you sell on Monday, the trade settles on Wednesday at 8 AM. The default delayed settlement rule for Future and Option contracts is T+1 at 8 AM.</p>
