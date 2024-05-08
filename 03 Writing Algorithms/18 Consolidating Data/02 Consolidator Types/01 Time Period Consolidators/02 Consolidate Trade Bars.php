<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/trade-bar-definition.php");

$dataFormatInfo = new TradeBarConsolidatorFormatInfo();

$timeSpanPeriod = "FromDays(1)";
$timeDeltaPeriod = "days=1";
$resolutionPeriod = "Daily";
$createConsolidatorExtraArgs = "";
$resolveConsolidatorExtraArgsC = "";
$resolveConsolidatorExtraArgsPy = "";
$resolutionArgExtraExamplesC = "_consolidator = TradeBarConsolidator.FromResolution(Resolution.Daily);
// Alias:
// ";
$resolutionArgExtraExamplesPy = "self._consolidator = TradeBarConsolidator.from_resolution(Resolution.DAILY)
# Alias:
# ";
$consolidationTextResolution = "minute";
$consolidationTextReceiveTime1 = "at 9:31";
$consolidationTextReceiveTime2 = "9:31";
$shortCutTickTypeArg = "";

$consolidatorInfo = new TimePeriodConsolidatorInfo($timeSpanPeriod, 
	$timeDeltaPeriod, 
	$resolutionPeriod, 
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
