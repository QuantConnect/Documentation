<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/tick-definition.php");
$output = "<code>RenkoBar</code> objects";
$consolidationHandlerType = "RenkoBar";
$dataFormatInfo = new TickConsolidatorFormatInfo($output, $consolidationHandlerType);

$consolidatorInfo = new RenkoConsolidatorInfo();

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
