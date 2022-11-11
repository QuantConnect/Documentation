<p>We model buying power and margin calls to ensure your algorithm stays within the margin requirements.</p>

<h4>Buying Power</h4>
<p>In the US, Wolverine allows up to 2x leverage on Equity trades for margin accounts. In other countries, Wolverine may offer different amounts of leverage. To figure out how much leverage you can access, check with your local legislation or contact a Wolverine representative. We model the US version of Wolverine leverage by default.</p>

<?php include(DOCS_RESOURCES."/brokerages/margin-calls.html"); ?>

<?php 
include(DOCS_RESOURCES."/brokerages/pattern-day-trader-rule.php"); 
$getPDTText();
?>
