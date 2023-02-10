<?php

include(DOCS_RESOURCES."/consolidators/consolidator-info/base-class-definition.php");

if (class_exists('VolumeRenkoConsolidatorInfo')) return;

class VolumeRenkoConsolidatorInfo extends ConsolidatorInfo
{
	function __construct()
	{
		parent::__construct();
		$this->basedOnText = " custom set volume traded";
		$this->create_consolidator_resource_path = "/consolidators/consolidator-info/volume-renko/create-consolidator.html";
		$this->define_handler_resource_path = "/consolidators/consolidator-info/volume-renko/define-handler.html";
	}
}
?>