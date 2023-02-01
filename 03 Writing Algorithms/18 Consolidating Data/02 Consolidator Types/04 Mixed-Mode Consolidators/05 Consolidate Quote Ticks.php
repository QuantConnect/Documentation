<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/tick-quotebar-definition.php");
$dataFormatInfo = new TickQuoteBarConsolidatorFormatInfo();

$numSamples = 10;
$timeSpanPeriod = "FromMilliseconds(100)";
$timeDeltaPeriod = "milliseconds=100";
$consolidationTextResolution = "tick";
$consolidationTextReceiveTime1 = "milliseconds after 9:30";
$consolidationTextReceiveTime2 = $consolidationTextReceiveTime1;
$consolidatorInfo = new MixedModeConsolidatorInfo($numSamples,
	$timeSpanPeriod,
	$timeDeltaPeriod,
	$consolidationTextResolution,
	$consolidationTextReceiveTime1,
	$consolidationTextReceiveTime2);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
