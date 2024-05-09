<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/tick-quotebar-definition.php");
$dataFormatInfo = new TickQuoteBarConsolidatorFormatInfo();

$createConsolidatorExtraArgsC = ", TickType.Quote";
$createConsolidatorExtraArgsPy = ", TickType.QUOTE";
$shortCutTickTypeArgC = "TickType.Quote, ";
$shortCutTickTypeArgPy = "TickType.QUOTE, ";

$consolidatorInfo = new CalendarConsolidatorInfo($createConsolidatorExtraArgsC, $createConsolidatorExtraArgsPy, $shortCutTickTypeArgC, $shortCutTickTypeArgPy);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
