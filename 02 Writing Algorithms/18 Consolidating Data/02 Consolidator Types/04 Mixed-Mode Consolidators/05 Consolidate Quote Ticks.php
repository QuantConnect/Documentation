<?php 
include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
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
$getConsolidatorText(new TickQuoteBarConsolidatorFormatInfo(), $consolidatorInfo);
?>
