<?
$cSharpPrefix = "";
$pythonPrefix = "self.";
$sampleDataLink = "/docs/v2/writing-algorithms/object-store#03-Create-Sample-Data";
$writingAlgorithmsText = "To avoid slowing down your backtests, save data once in the <code>OnEndOfAlgorithm</code> event handler. In live trading, you can save data more frequently like at the end of a <code>Train</code> method or after universe selection.";
include(DOCS_RESOURCES."/object-store/save-data.php");
?>