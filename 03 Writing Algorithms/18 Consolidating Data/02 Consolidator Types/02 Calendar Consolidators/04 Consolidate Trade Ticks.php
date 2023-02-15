<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/tick-definition.php");

$dataFormatInfo = new TickConsolidatorFormatInfo();

$createConsolidatorExtraArgs = "";
$shortCutTickTypeArg = "";

$consolidatorInfo = new CalendarConsolidatorInfo($createConsolidatorExtraArgs, $shortCutTickTypeArg);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
