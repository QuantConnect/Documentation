<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/quote-bar-definition.php");
$dataFormatInfo = new QuoteBarConsolidatorFormatInfo();

$timeSpanPeriod = "FromDays(1)";
$timeSpanStartTime = "9, 30, 0";
$timeDeltaPeriod = "days=1";
$timeDeltaStartTime = "hours=9, minutes=30";
$resolutionPeriodC = "Daily";
$resolutionPeriodPy = "DAILY";
$createConsolidatorExtraArgsC = "";
$createConsolidatorExtraArgsPy = "";
$resolveConsolidatorExtraArgsC = "";
$resolveConsolidatorExtraArgsPy = "";
$resolutionArgExtraExamplesC = "";
$resolutionArgExtraExamplesPy = "";
$consolidationTextResolution = "minute";
$consolidationTextReceiveTime1 = "at 9:31";
$consolidationTextReceiveTime2 = "9:31";
$shortCutTickTypeArg = "TickType.Quote, ";

$consolidatorInfo = new TimePeriodConsolidatorInfo($timeSpanPeriod, 
	$timeSpanStartTime, 
	$timeDeltaPeriod,
	$timeDeltaStartTime,
	$resolutionPeriodC, 
	$resolutionPeriodPy,
	$createConsolidatorExtraArgsC,
	$createConsolidatorExtraArgsPy,
	$resolveConsolidatorExtraArgsC,
	$resolveConsolidatorExtraArgsPy,
	$resolutionArgExtraExamplesC,
	$resolutionArgExtraExamplesPy,
	$consolidationTextResolution,
	$consolidationTextReceiveTime1,
	$consolidationTextReceiveTime2,
	$shortCutTickTypeArg);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
