<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/trade-bar-definition.php");

$dataFormatInfo = new TradeBarConsolidatorFormatInfo();

$createConsolidatorExtraArgsC = "";
$createConsolidatorExtraArgsPy = "";
$shortCutTickTypeArgC = "";
$shortCutTickTypeArgPy = "";

$consolidatorInfo = new CalendarConsolidatorInfo($createConsolidatorExtraArgsC, $createConsolidatorExtraArgsPy, $shortCutTickTypeArgC, $shortCutTickTypeArgPy);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
