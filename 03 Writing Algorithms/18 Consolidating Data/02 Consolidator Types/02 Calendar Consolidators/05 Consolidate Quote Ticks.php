<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/tick-quotebar-definition.php");
$dataFormatInfo = new TickQuoteBarConsolidatorFormatInfo();

$createConsolidatorExtraArgs = ", TickType.Quote";
$shortcutClass1 = " class='csharp'"; // # Python currently unavailable. See https://github.com/QuantConnect/Lean/issues/6814
$shortcutClass2 = "csharp";
$shortCutTickTypeArg = "TickType.Quote, ";

$consolidatorInfo = new CalendarConsolidatorInfo($createConsolidatorExtraArgs, $shortcutClass1, $shortcutClass2, $shortCutTickTypeArg);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
