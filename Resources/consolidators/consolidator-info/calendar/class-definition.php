<?php

include(DOCS_RESOURCES."/consolidators/consolidator-info/base-class-definition.php");

class CalendarConsolidatorInfo extends ConsolidatorInfo
{
	public $createConsolidatorExtraArgs,
	 	$shortcutClass1,
	 	$shortcutClass2,
	 	$shortCutTickTypeArg;

	function __construct($createConsolidatorExtraArgs, $shortcutClass1, $shortcutClass2, $shortCutTickTypeArg)
	{
		parent::__construct();
		$this->createConsolidatorExtraArgs = $createConsolidatorExtraArgs;
		$this->shortcutClass1 = $shortcutClass1;
		$this->shortcutClass2 = $shortcutClass2;
		$this->shortCutTickTypeArg = $shortCutTickTypeArg;
		$this->basedOnText = "custom start and end periods";
		$this->create_consolidator_resource_path = "/consolidators/consolidator-info/calendar/create-consolidator.php";
		$this->define_handler_resource_path = "/consolidators/consolidator-info/calendar/define-handler.html";
		$this->shortcut_resource_path = "/consolidators/consolidator-info/calendar/shortcut.php";
	}
}
?>