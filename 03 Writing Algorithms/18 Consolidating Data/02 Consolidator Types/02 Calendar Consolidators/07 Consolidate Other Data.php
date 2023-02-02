<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/dynamic-data-definition.php");

$dataFormatInfo = new DynamicDataConsolidatorFormatInfo();

$createConsolidatorExtraArgs = "";
$shortcutClass1 = "";
$shortcutClass2 = "";
$shortCutTickTypeArg = "";

$consolidatorInfo = new CalendarConsolidatorInfo($createConsolidatorExtraArgs, $shortcutClass1, $shortcutClass2, $shortCutTickTypeArg);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
