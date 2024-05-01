<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/trade-bar-definition.php");
$output = "<code>RangeBar</code> objects";
$consolidationHandlerType = "RangeBar";
$dataFormatInfo = new TradeBarConsolidatorFormatInfo($output, $consolidationHandlerType);

$extraExamples = file_get_contents(DOCS_RESOURCES."/consolidators/consolidator-info/classic-range/trade-bar-extra-examples.html");
$consolidatorInfo = new ClassicRangeConsolidatorInfo($extraExamples);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
