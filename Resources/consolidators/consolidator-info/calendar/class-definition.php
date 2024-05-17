<?php

include(DOCS_RESOURCES."/consolidators/consolidator-info/base-class-definition.php");

if (class_exists('CalendarConsolidatorInfo')) return;

class CalendarConsolidatorInfo extends ConsolidatorInfo
{
	public $createConsolidatorExtraArgsC,
	        $createConsolidatorExtraArgsPy,
	 	$shortCutTickTypeArgC,
	        $shortCutTickTypeArgPy;

	function __construct($createConsolidatorExtraArgsC, $createConsolidatorExtraArgsPy, $shortCutTickTypeArgC, $shortCutTickTypeArgPy)
	{
		parent::__construct();
		$this->createConsolidatorExtraArgsC = $createConsolidatorExtraArgsC;
		$this->createConsolidatorExtraArgsPy = $createConsolidatorExtraArgsPy;
		$this->shortCutTickTypeArgC = $shortCutTickTypeArgC;
		$this->shortCutTickTypeArgPy = $shortCutTickTypeArgPy;
		$this->basedOnText = "custom start and end periods";
		$this->create_consolidator_resource_path = "/consolidators/consolidator-info/calendar/create-consolidator.php";
		$this->define_handler_resource_path = "/consolidators/consolidator-info/calendar/define-handler.html";
		$this->shortcut_resource_path = "/consolidators/consolidator-info/calendar/shortcut.php";
	}
}
?>
