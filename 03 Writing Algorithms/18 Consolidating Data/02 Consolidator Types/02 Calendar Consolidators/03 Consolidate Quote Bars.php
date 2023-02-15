<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/quote-bar-definition.php");
$dataFormatInfo = new QuoteBarConsolidatorFormatInfo();

$createConsolidatorExtraArgs = "";
$shortCutTickTypeArg = "TickType.Quote, ";

$consolidatorInfo = new CalendarConsolidatorInfo($createConsolidatorExtraArgs, $shortCutTickTypeArg);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
