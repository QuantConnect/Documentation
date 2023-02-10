<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/tick-definition.php");
$output = "<code>VolumeRenkoBar</code> objects";
$consolidationHandlerType = "VolumeRenkoBar";
$dataFormatInfo = new TickConsolidatorFormatInfo($output, $consolidationHandlerType);

$consolidatorInfo = new VolumeRenkoConsolidatorInfo();

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
