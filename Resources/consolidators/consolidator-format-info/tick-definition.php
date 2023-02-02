<?php

include(DOCS_RESOURCES."/consolidators/consolidator-format-info/base-class-definition.php");

if (class_exists('TickConsolidatorFormatInfo')) return;

class TickConsolidatorFormatInfo extends ConsolidatorFormatInfo
{
	function __construct($output = "<code>TradeBar</code> objects", $consolidationHandlerType = "TradeBar")
	{
		$this->output = $output;
		$this->consolidationHandlerType = $consolidationHandlerType;
		$this->textName = "<code>Tick</code>";
		$this->className = "TickConsolidator";
		$this->input = "<code>Tick</code> objects";
		$this->typeOf = "Tick";
		$this->manualUpdateCode = file_get_contents(DOCS_RESOURCES."/consolidators/tick-manual-update.html");
		$this->isSecurityData = true;
	}
}

?>
