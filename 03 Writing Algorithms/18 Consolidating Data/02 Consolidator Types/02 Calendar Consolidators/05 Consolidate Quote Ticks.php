<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/tick-quotebar-definition.php");
$dataFormatInfo = new TickQuoteBarConsolidatorFormatInfo();

$createConsolidatorExtraArgs = ", TickType.Quote";
$shortCutTickTypeArg = "TickType.Quote, ";

$consolidatorInfo = new CalendarConsolidatorInfo($createConsolidatorExtraArgs, $shortCutTickTypeArg);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
