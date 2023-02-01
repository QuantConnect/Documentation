<?php

include(DOCS_RESOURCES."/consolidators/consolidator-format-info/base-class-definition.php");

class TickQuoteBarConsolidatorFormatInfo extends ConsolidatorFormatInfo
{
	function __construct($output = "<code>QuoteBar</code> objects", $consolidationHandlerType = "QuoteBar")
	{
		$this->output = $output;
		$this->consolidationHandlerType = $consolidationHandlerType;
		$this->textName = "<code>Tick</code> quote bar";
		$this->className = "TickQuoteBarConsolidator";
		$this->input = "<code>Tick</code> objects that represent quotes";
		$this->typeOf = "Tick";
		$this->manualUpdateCode = file_get_contents(DOCS_RESOURCES."/consolidators/tick-manual-update.html");
	}
}

?>
