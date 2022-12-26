<?php 
include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
$output = "<code>RenkoBar</code> objects";
$consolidationHandlerType = "RenkoBar";
$dataFormatInfo = new TickQuoteBarConsolidatorFormatInfo($output, $consolidationHandlerType);

$consolidatorInfo = new ClassicRenkoConsolidatorInfo();

$getConsolidatorText($dataFormatInfo, $consolidatorInfo);
?>
