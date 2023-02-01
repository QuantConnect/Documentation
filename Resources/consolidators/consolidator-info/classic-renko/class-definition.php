<?php

include(DOCS_RESOURCES."/consolidators/consolidator-info/base-class-definition.php");

class ClassicRenkoConsolidatorInfo extends ConsolidatorInfo
{
	public $extraExamples;

	function __construct($extraExamples = "")
	{
		parent::__construct();
		$this->basedOnText = " the preceding Renko bar rules";
		$this->extraExamples = $extraExamples;
		$this->create_consolidator_resource_path = "/consolidators/consolidator-info/classic-renko/create-consolidator.php";
		$this->define_handler_resource_path = "/consolidators/consolidator-info/classic-renko/define-handler.html";
	}
}
?>



