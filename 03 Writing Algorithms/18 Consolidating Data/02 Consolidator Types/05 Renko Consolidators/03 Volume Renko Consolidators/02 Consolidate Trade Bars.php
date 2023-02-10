<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/trade-bar-definition.php");

$output = "<code>VolumeRenkoBar</code> objects";
$consolidationHandlerType = "VolumeRenkoBar";
$dataFormatInfo = new TradeBarConsolidatorFormatInfo($output, $consolidationHandlerType);

$consolidatorInfo = new VolumeRenkoConsolidatorInfo();
include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
