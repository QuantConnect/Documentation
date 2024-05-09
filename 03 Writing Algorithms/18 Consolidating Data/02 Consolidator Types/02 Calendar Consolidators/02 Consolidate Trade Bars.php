<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/trade-bar-definition.php");

$dataFormatInfo = new TradeBarConsolidatorFormatInfo();

$createConsolidatorExtraArgs = "";
$shortCutTickTypeArgC = "";
$shortCutTickTypeArgPy = "";

$consolidatorInfo = new CalendarConsolidatorInfo($createConsolidatorExtraArgs, $shortCutTickTypeArgC, $shortCutTickTypeArgPy);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
