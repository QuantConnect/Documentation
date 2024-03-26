<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/trade-bar-definition.php");

$output = "<code>RangeBar</code> objects";
$consolidationHandlerType = "RangeBar";
$dataFormatInfo = new TradeBarConsolidatorFormatInfo($output, $consolidationHandlerType);

$consolidatorInfo = new RangeConsolidatorInfo();
include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
