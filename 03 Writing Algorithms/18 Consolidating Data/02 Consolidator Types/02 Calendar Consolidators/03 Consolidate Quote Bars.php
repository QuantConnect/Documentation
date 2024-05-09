<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/quote-bar-definition.php");
$dataFormatInfo = new QuoteBarConsolidatorFormatInfo();

$createConsolidatorExtraArgsC = "";
$createConsolidatorExtraArgsPy = "";
$shortCutTickTypeArgC = "TickType.Quote, ";
$shortCutTickTypeArgPy = "TickType.QUOTE, ";

$consolidatorInfo = new CalendarConsolidatorInfo($createConsolidatorExtraArgsC, $createConsolidatorExtraArgsPy, $shortCutTickTypeArgC, $shortCutTickTypeArgPy);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
