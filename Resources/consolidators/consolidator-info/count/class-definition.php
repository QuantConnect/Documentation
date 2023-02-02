<?php

include(DOCS_RESOURCES."/consolidators/consolidator-info/base-class-definition.php");

if (class_exists('CountConsolidatorInfo')) return;

class CountConsolidatorInfo extends ConsolidatorInfo
{
	public $numSamples;

	function __construct($numSamples)
	{
		parent::__construct();
		$this->numSamples = $numSamples;
		$this->basedOnText = "a number of samples";
		$this->create_consolidator_resource_path = "/consolidators/consolidator-info/count/create-consolidator.php";
		$this->define_handler_resource_path = "/consolidators/consolidator-info/count/define-handler.html";
	}
}
?>