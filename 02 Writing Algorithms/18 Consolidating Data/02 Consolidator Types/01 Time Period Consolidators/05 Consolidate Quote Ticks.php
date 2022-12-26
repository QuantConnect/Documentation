<?php 
include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
$dataFormatInfo = new TickQuoteBarConsolidatorFormatInfo();

$timeSpanPeriod = "FromMilliseconds(100)";
$timeDeltaPeriod = "milliseconds=100";
$resolutionPeriod = "Second";
$typeOf = "Tick";
$createConsolidatorExtraArgs = ", TickType.Quote";
$resolveConsolidatorExtraArgsC = ", typeof(QuoteBar)";
$resolutionArgExtraExamplesC = "";
$resolutionArgExtraExamplesPy = "";
$consolidationHandlerType = "QuoteBar";
$consolidationTextResolution = "tick";
$consolidationTextReceiveTime1 = "milliseconds after 9:30";
$consolidationTextReceiveTime2 = $consolidationTextReceiveTime1;
$shortcutClass1 = " class='csharp'"; // # Python currently unavailable. See https://github.com/QuantConnect/Lean/issues/6814
$shortcutClass2 = "csharp";
$shortCutTickTypeArg = "TickType.Quote, ";

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
	$shortcutClass1,
	$shortcutClass2,
	$shortCutTickTypeArg);

$getConsolidatorText($dataFormatInfo, $consolidatorInfo);
?>
