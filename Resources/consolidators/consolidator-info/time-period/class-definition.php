<?php

include(DOCS_RESOURCES."/consolidators/consolidator-info/base-class-definition.php");

if (class_exists('TimePeriodConsolidatorInfo')) return;

class TimePeriodConsolidatorInfo extends ConsolidatorInfo
{
	public $timeSpanPeriod, 
		$timeDeltaPeriod, 
		$resolutionPeriodC, 
	        $resolutionPeriodPy, 
		$createConsolidatorExtraArgs,
		$resolveConsolidatorExtraArgsC,
		$resolveConsolidatorExtraArgsPy,
		$resolutionArgExtraExamplesC,
		$consolidationTextResolution,
		$consolidationTextReceiveTime1,
		$consolidationTextReceiveTime2,
		$manualUpdateCode;

	function __construct($timeSpanPeriod, 
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
		$shortCutTickTypeArg)
	{
		parent::__construct();
		$this->timeSpanPeriod = $timeSpanPeriod;
		$this->timeDeltaPeriod = $timeDeltaPeriod;
		$this->resolutionPeriodC = $resolutionPeriodC;
		$this->resolutionPeriodPy = $resolutionPeriodPy;
		$this->createConsolidatorExtraArgs = $createConsolidatorExtraArgs;
		$this->resolveConsolidatorExtraArgsC = $resolveConsolidatorExtraArgsC;
		$this->resolveConsolidatorExtraArgsPy = $resolveConsolidatorExtraArgsPy;
		$this->resolutionArgExtraExamplesC = $resolutionArgExtraExamplesC;
		$this->resolutionArgExtraExamplesPy = $resolutionArgExtraExamplesPy;
		$this->consolidationTextResolution = $consolidationTextResolution;
		$this->consolidationTextReceiveTime1 = $consolidationTextReceiveTime1;
		$this->consolidationTextReceiveTime2 = $consolidationTextReceiveTime2;
		$this->shortCutTickTypeArg = $shortCutTickTypeArg;
		$this->basedOnText = "a period of time";
		$this->create_consolidator_resource_path = "/consolidators/consolidator-info/time-period/create-consolidator.php";
		$this->define_handler_resource_path = "/consolidators/consolidator-info/time-period/define-handler.php";
		$this->shortcut_resource_path = "/consolidators/consolidator-info/time-period/shortcut.php";
	}
}
?>
