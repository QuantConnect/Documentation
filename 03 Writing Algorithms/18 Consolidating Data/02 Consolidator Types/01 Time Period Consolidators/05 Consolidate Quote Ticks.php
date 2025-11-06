<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/tick-quotebar-definition.php");

$dataFormatInfo = new TickQuoteBarConsolidatorFormatInfo();

$timeSpanPeriod = "FromMilliseconds(100)";
$timeSpanStartTime = "9, 30, 0";
$timeDeltaPeriod = "milliseconds=100";
$timeDeltaStartTime = "hours=9, minutes=30";
$resolutionPeriodC = "Second";
$resolutionPeriodPy = "SECOND";
$typeOf = "Tick";
$createConsolidatorExtraArgsC = ", TickType.Quote";
$createConsolidatorExtraArgsPy = ", TickType.QUOTE";
$resolveConsolidatorExtraArgsC = ", typeof(QuoteBar)";
$resolveConsolidatorExtraArgsPy = "";
$resolutionArgExtraExamplesC = "";
$resolutionArgExtraExamplesPy = "";
$consolidationHandlerType = "QuoteBar";
$consolidationTextResolution = "tick";
$consolidationTextReceiveTime1 = "milliseconds after 9:30";
$consolidationTextReceiveTime2 = $consolidationTextReceiveTime1;
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
