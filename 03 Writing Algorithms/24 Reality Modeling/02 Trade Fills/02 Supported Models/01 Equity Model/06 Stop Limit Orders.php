<?php 
$orderType = "stop limit";
$followingTable = true;
include(DOCS_RESOURCES."/reality-modeling/trade-fills/equity-fill-model-logic-1.php");
?>

<p>The following table describes how the fill model processes the order given the data format and order direction. Once the stop condition is met, the model starts to check the fill condition. Once the fill condition is met, the model fills the orders and sets the fill price.</p>

<?php
$includeIntro = false;
include(DOCS_RESOURCES."/reality-modeling/trade-fills/stop-limit-orders.php"); 
?>

<p>The model only fills stop limit orders when the exchange is open.</p>

