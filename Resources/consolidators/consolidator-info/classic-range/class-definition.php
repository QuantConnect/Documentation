<?php

include(DOCS_RESOURCES."/consolidators/consolidator-info/base-class-definition.php");

if (class_exists('ClassicRangeConsolidatorInfo')) return;

class ClassicRangeConsolidatorInfo extends ConsolidatorInfo
{
	public $extraExamples;

	function __construct($extraExamples = "")
	{
		parent::__construct();
		$this->basedOnText = " the preceding Range bar rules";
		$this->extraExamples = $extraExamples;
		$this->create_consolidator_resource_path = "/consolidators/consolidator-info/classic-range/create-consolidator.php";
		$this->define_handler_resource_path = "/consolidators/consolidator-info/classic-range/define-handler.html";
	}
}
?>



