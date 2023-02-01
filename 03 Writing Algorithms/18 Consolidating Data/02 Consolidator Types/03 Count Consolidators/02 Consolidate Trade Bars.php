<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/trade-bar-definition.php");

$dataFormatInfo = new TradeBarConsolidatorFormatInfo();
$consolidatorInfo = new CountConsolidatorInfo(10);
include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
