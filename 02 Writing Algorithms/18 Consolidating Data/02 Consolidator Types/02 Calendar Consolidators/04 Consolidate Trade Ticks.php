<?php 
include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
$dataFormatInfo = new TickConsolidatorFormatInfo();

$createConsolidatorExtraArgs = "";
$shortcutClass1 = "";
$shortcutClass2 = "";
$shortCutTickTypeArg = "";

$consolidatorInfo = new CalendarConsolidatorInfo($createConsolidatorExtraArgs, $shortcutClass1, $shortcutClass2, $shortCutTickTypeArg);

$getConsolidatorText($dataFormatInfo, $consolidatorInfo);
?>
