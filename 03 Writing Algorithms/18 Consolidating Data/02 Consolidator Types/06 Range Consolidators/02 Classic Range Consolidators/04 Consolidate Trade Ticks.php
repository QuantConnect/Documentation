<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/tick-definition.php");
$output = "<code>RangeBar</code> objects";
$consolidationHandlerType = "RangeBar";
$dataFormatInfo = new TickConsolidatorFormatInfo($output, $consolidationHandlerType);

$consolidatorInfo = new ClassicRangeConsolidatorInfo();

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
