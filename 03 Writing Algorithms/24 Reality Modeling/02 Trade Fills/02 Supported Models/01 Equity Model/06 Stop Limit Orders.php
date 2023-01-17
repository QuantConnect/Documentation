<?php 
$orderType = "stop limit";
$followingTable = true;
include(DOCS_RESOURCES."/reality-modeling/trade-fills/equity-fill-model-logic-1.php");

echo "<p>Once the stop condition is met, the model starts to check the fill condition. Once the fill condition is met, the model fills the orders and sets the fill price.</p>";

$includeIntro = false;
include(DOCS_RESOURCES."/reality-modeling/trade-fills/stop-limit-orders.php"); 
?>

