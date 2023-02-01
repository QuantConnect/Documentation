<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/trade-bar-definition.php");

$dataFormatInfo = new TradeBarConsolidatorFormatInfo();

$numSamples = 10;
$timeSpanPeriod = "FromDays(1)";
$timeDeltaPeriod = "days=1";
$consolidationTextResolution = "minute";
$consolidationTextReceiveTime1 = "at 9:31";
$consolidationTextReceiveTime2 = "9:31";
$consolidatorInfo = new MixedModeConsolidatorInfo($numSamples,
	$timeSpanPeriod,
	$timeDeltaPeriod,
	$consolidationTextResolution,
	$consolidationTextReceiveTime1,
	$consolidationTextReceiveTime2);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
