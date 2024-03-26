<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/tick-quotebar-definition.php");
$output = "<code>RangeBar</code> objects";
$consolidationHandlerType = "RangeBar";
$dataFormatInfo = new TickQuoteBarConsolidatorFormatInfo($output, $consolidationHandlerType);

$consolidatorInfo = new RangeConsolidatorInfo();

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
