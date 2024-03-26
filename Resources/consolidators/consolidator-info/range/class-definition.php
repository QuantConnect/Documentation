<?php

include(DOCS_RESOURCES."/consolidators/consolidator-info/base-class-definition.php");

if (class_exists('RangeConsolidatorInfo')) return;

class RangeConsolidatorInfo extends ConsolidatorInfo
{
	function __construct()
	{
		parent::__construct();
		$this->basedOnText = " the preset range of price movement";
		$this->create_consolidator_resource_path = "/consolidators/consolidator-info/range/create-consolidator.html";
		$this->define_handler_resource_path = "/consolidators/consolidator-info/range/define-handler.html";
	}
}
?>