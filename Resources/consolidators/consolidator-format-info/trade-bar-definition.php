<?php

include(DOCS_RESOURCES."/consolidators/consolidator-format-info/base-class-definition.php");

if (class_exists('TradeBarConsolidatorFormatInfo')) return;

class TradeBarConsolidatorFormatInfo extends ConsolidatorFormatInfo
{
	function __construct($output = "<code>TradeBar</code> objects of the same size or larger", $consolidationHandlerType = "TradeBar")
	{
		$this->output = $output;
		$this->consolidationHandlerType = $consolidationHandlerType;
		$this->textName = "<code>TradeBar</code>";
		$this->className = "TradeBarConsolidator";
		$this->input = "<code>TradeBar</code> objects";
		$this->typeOf = "TradeBar";
		$this->manualUpdateCode = file_get_contents(DOCS_RESOURCES."/consolidators/trade-bar-manual-update.html");
		$this->isSecurityData = true;
	}
}

?>
