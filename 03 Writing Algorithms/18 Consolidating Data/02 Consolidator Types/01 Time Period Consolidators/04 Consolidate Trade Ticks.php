<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/tick-definition.php");

$dataFormatInfo = new TickConsolidatorFormatInfo();

$timeSpanPeriod = "FromMilliseconds(100)";
$timeSpanStartTime = "9, 30, 0";
$timeDeltaPeriod = "milliseconds=100";
$timeDeltaStartTime = "hours=9, minutes=30";
$resolutionPeriodC = "Second";
$resolutionPeriodPy = "SECOND";
$createConsolidatorExtraArgsC = "";
$createConsolidatorExtraArgsPy = "";
$resolveConsolidatorExtraArgsC = "";
$resolveConsolidatorExtraArgsPy = "";
$resolutionArgExtraExamplesC = "";
$resolutionArgExtraExamplesPy = "";
$consolidationTextResolution = "tick";
$consolidationTextReceiveTime1 = "milliseconds after 9:30";
$consolidationTextReceiveTime2 = $consolidationTextReceiveTime1;
$shortCutTickTypeArg = "";

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
