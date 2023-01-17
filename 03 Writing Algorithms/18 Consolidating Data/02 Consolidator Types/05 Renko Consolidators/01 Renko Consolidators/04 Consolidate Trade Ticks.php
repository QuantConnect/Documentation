<?php 
include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
$output = "<code>RenkoBar</code> objects";
$consolidationHandlerType = "RenkoBar";
$dataFormatInfo = new TickConsolidatorFormatInfo($output, $consolidationHandlerType);

$consolidatorInfo = new RenkoConsolidatorInfo();

$getConsolidatorText($dataFormatInfo, $consolidatorInfo);
?>
