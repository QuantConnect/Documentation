<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/tick-definition.php");

$dataFormatInfo = new TickConsolidatorFormatInfo();

$createConsolidatorExtraArgs = "";
$shortCutTickTypeArgC = "";
$shortCutTickTypeArgPy = "";

$consolidatorInfo = new CalendarConsolidatorInfo($createConsolidatorExtraArgs, $shortCutTickTypeArgC, $shortCutTickTypeArgPy);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
