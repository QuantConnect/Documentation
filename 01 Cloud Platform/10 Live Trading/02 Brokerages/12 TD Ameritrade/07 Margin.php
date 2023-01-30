<p>We model buying power and margin calls to ensure your algorithm stays within the margin requirements. If you set the brokerage model to a different model, the new brokerage model defines how margin is modeled.</p>

<h4>Buying Power</h4>
<p>In the US, TD Ameritrade allows up to 2x leverage on Equity trades for margin accounts. In other countries, TD Ameritrade may offer different amounts of leverage. To figure out how much leverage you can access, check with your local legislation or contact a TD Ameritrade representative. We model the US version of TD Ameritrade leverage by default.</p>

<?php include(DOCS_RESOURCES."/brokerages/margin-calls.html"); ?>

<?php 
$includeh4=true 
include(DOCS_RESOURCES."/brokerages/pattern-day-trader-rule.php"); 
?>
