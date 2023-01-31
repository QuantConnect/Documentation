<?php 
$orderType = "stop market";
$followingTable = true;
include(DOCS_RESOURCES."/reality-modeling/trade-fills/equity-fill-model-logic-1.php");
?>

<p>Once the stop condition is met, the model fills the orders and sets the fill price.</p>

<?php
$includeIntro = false;
include(DOCS_RESOURCES."/reality-modeling/trade-fills/stop-market-orders.php"); 
?>

