<?php

include(DOCS_RESOURCES."/consolidators/consolidator-info/base-class-definition.php");

if (class_exists('MarketHourAwareConsolidatorInfo')) return;

class MarketHourAwareConsolidatorInfo extends ConsolidatorInfo
{
	public $dataTypeArgC,
		$dataTypeArgPy,
		$tickTypeArgC,
		$tickTypeArgPy;

	function __construct($dataTypeArgC, $dataTypeArgPy, $tickTypeArgC, $tickTypeArgPy)
	{
		parent::__construct();
		$this->dataTypeArgC = $dataTypeArgC;
		$this->dataTypeArgPy = $dataTypeArgPy;
		$this->tickTypeArgC = $tickTypeArgC;
		$this->tickTypeArgPy = $tickTypeArgPy;
		$this->basedOnText = "regular or extended market hours";
		$this->create_consolidator_resource_path = "/consolidators/consolidator-info/market-hour-aware/create-consolidator.php";
		$this->define_handler_resource_path = "/consolidators/consolidator-info/market-hour-aware/define-handler.html";
	}
}
?>
