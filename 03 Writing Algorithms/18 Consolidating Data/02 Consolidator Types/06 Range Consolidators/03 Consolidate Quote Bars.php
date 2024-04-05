<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/quote-bar-definition.php");
$output = "<code>RangeBar</code> objects";
$consolidationHandlerType = "RangeBar";
$dataFormatInfo = new QuoteBarConsolidatorFormatInfo($output, $consolidationHandlerType);

$consolidatorInfo = new RangeConsolidatorInfo();

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
