<?php

include(DOCS_RESOURCES."/consolidators/consolidator-info/base-class-definition.php");

if (class_exists('RenkoConsolidatorInfo')) return;

class RenkoConsolidatorInfo extends ConsolidatorInfo
{
	function __construct()
	{
		parent::__construct();
		$this->basedOnText = " the traditional Renko bar rules";
		$this->create_consolidator_resource_path = "/consolidators/consolidator-info/renko/create-consolidator.html";
		$this->define_handler_resource_path = "/consolidators/consolidator-info/renko/define-handler.html";
	}
}
?>