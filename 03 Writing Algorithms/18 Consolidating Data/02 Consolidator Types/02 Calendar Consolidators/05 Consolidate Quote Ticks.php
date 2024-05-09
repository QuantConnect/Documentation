<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/tick-quotebar-definition.php");
$dataFormatInfo = new TickQuoteBarConsolidatorFormatInfo();

$createConsolidatorExtraArgs = ", TickType.Quote";
$shortCutTickTypeArgC = "TickType.Quote, ";
$shortCutTickTypeArgPy = "TickType.QUOTE, ";

$consolidatorInfo = new CalendarConsolidatorInfo($createConsolidatorExtraArgs, $shortCutTickTypeArgC, $shortCutTickTypeArgPy);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
