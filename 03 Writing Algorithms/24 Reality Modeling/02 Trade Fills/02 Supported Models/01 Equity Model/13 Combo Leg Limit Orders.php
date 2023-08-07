<?php 
$orderType = "combo leg limit";
$followingTable = true;
include(DOCS_RESOURCES."/reality-modeling/trade-fills/equity-fill-model-logic-1.php");
?>

<p>The order direction in the table represents the order direction of the order leg, not the order direction of the combo order.</p>

<?php
$includeIntro = false;
include(DOCS_RESOURCES."/reality-modeling/trade-fills/combo-leg-limit-orders.php"); 
?>

