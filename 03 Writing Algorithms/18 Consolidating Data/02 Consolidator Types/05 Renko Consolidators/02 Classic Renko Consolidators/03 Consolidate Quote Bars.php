<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/quote-bar-definition.php");
$output = "<code>RenkoBar</code> objects";
$consolidationHandlerType = "RenkoBar";
$dataFormatInfo = new QuoteBarConsolidatorFormatInfo($output, $consolidationHandlerType);

$extraExamples = echo file_get_contents(DOCS_RESOURCES."/consolidators/consolidator-info/classic-renko/quote-bar-extra-examples.html");
$consolidatorInfo = new ClassicRenkoConsolidatorInfo($extraExamples);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
