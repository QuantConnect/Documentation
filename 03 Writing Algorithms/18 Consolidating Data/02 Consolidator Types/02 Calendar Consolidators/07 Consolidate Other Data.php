<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/dynamic-data-definition.php");

$dataFormatInfo = new DynamicDataConsolidatorFormatInfo();

$createConsolidatorExtraArgsC = "";
$createConsolidatorExtraArgsPy = "";
$shortCutTickTypeArgC = "";
$shortCutTickTypeArgPy = "";

$consolidatorInfo = new CalendarConsolidatorInfo($createConsolidatorExtraArgsC, $createConsolidatorExtraArgsPy, $shortCutTickTypeArgC, $shortCutTickTypeArgPy);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
