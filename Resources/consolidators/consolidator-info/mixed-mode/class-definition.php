<?php

include(DOCS_RESOURCES."/consolidators/consolidator-info/base-class-definition.php");

if (class_exists('MixedModeConsolidatorInfo')) return;

class MixedModeConsolidatorInfo extends ConsolidatorInfo
{
	public $numSamples,
		$timeSpanPeriod,
		$timeDeltaPeriod,
		$consolidationTextResolution,
		$consolidationTextReceiveTime1,
		$consolidationTextReceiveTime2;

	function __construct($numSamples, $timeSpanPeriod, $timeDeltaPeriod, $consolidationTextResolution, $consolidationTextReceiveTime1, $consolidationTextReceiveTime2)
	{
		parent::__construct();
		$this->numSamples = $numSamples;
		$this->timeSpanPeriod = $timeSpanPeriod;
		$this->timeDeltaPeriod = $timeDeltaPeriod;
		$this->consolidationTextResolution = $consolidationTextResolution;
		$this->consolidationTextReceiveTime1 = $consolidationTextReceiveTime1;
		$this->consolidationTextReceiveTime2 = $consolidationTextReceiveTime2;
		$this->basedOnText = "a period of time or a number of samples, whichever occurs first";
		$this->create_consolidator_resource_path = "/consolidators/consolidator-info/mixed-mode/create-consolidator.php";
		$this->define_handler_resource_path = "/consolidators/consolidator-info/mixed-mode/define-handler.php";
	}
}
?>