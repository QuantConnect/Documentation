<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/dynamic-data-definition.php");

$dataFormatInfo = new DynamicDataConsolidatorFormatInfo();

$timeSpanPeriod = "FromDays(1)";
$timeDeltaPeriod = "days=1";
$resolutionPeriodC = "Daily";
$resolutionPeriodPy = "DAILY";
$createConsolidatorExtraArgs = "";
$resolveConsolidatorExtraArgsC = "";
$resolveConsolidatorExtraArgsPy = "";
$resolutionArgExtraExamplesC = "";
$resolutionArgExtraExamplesPy = "";
$consolidationTextResolution = "minute";
$consolidationTextReceiveTime1 = "at 9:31";
$consolidationTextReceiveTime2 = "9:31";
$shortCutTickTypeArg = "";

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
