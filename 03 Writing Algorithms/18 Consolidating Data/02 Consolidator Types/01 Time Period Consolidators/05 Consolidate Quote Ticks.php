<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/tick-quotebar-definition.php");

$dataFormatInfo = new TickQuoteBarConsolidatorFormatInfo();

$timeSpanPeriod = "FromMilliseconds(100)";
$timeDeltaPeriod = "milliseconds=100";
$resolutionPeriodC = "Second";
$resolutionPeriodPy = "SECOND";
$typeOf = "Tick";
$createConsolidatorExtraArgs = ", TickType.Quote";
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
	$timeDeltaPeriod, 
	$resolutionPeriodC, 
	$resolutionPeriodPy,
	$createConsolidatorExtraArgs,
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
