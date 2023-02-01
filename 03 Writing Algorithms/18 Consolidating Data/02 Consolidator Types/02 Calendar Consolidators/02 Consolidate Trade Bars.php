<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/trade-bar-definition.php");

$dataFormatInfo = new TradeBarConsolidatorFormatInfo();

$createConsolidatorExtraArgs = "";
$shortcutClass1 = "";
$shortcutClass2 = "";
$shortCutTickTypeArg = "";

$consolidatorInfo = new CalendarConsolidatorInfo($createConsolidatorExtraArgs, $shortcutClass1, $shortcutClass2, $shortCutTickTypeArg);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
