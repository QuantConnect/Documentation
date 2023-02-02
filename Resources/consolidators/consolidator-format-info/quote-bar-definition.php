<?php

include(DOCS_RESOURCES."/consolidators/consolidator-format-info/base-class-definition.php");

if (class_exists('QuoteBarConsolidatorFormatInfo')) return;

class QuoteBarConsolidatorFormatInfo extends ConsolidatorFormatInfo
{
	function __construct($output = "<code>QuoteBar</code> objects of the same size or larger", $consolidationHandlerType = "QuoteBar")
	{
		$this->output = $output;
		$this->consolidationHandlerType = $consolidationHandlerType;
		$this->textName = "<code>QuoteBar</code>";
		$this->className = "QuoteBarConsolidator";
		$this->input = "<code>QuoteBar</code> objects";
		$this->typeOf = "QuoteBar";
		$this->manualUpdateCode = file_get_contents(DOCS_RESOURCES."/consolidators/quote-bar-manual-update.html");
	}
}

?>
