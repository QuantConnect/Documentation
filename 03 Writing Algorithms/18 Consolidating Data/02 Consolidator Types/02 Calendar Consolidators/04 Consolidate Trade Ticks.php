<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/tick-definition.php");

$dataFormatInfo = new TickConsolidatorFormatInfo();

$createConsolidatorExtraArgs = "";
$shortcutClass1 = "";
$shortcutClass2 = "";
$shortCutTickTypeArg = "";

$consolidatorInfo = new CalendarConsolidatorInfo($createConsolidatorExtraArgs, $shortcutClass1, $shortcutClass2, $shortCutTickTypeArg);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
