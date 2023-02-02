<?php

if (class_exists('DynamicDataConsolidatorFormatInfo')) return;
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/base-class-definition.php");


class DynamicDataConsolidatorFormatInfo extends ConsolidatorFormatInfo
{
	function __construct()
	{
		$this->typeOf = "&lt;dataType&gt;";

		ob_start();
		include(DOCS_RESOURCES."/consolidators/consolidator-format-info/custom-data-output.php");
		$this->output = ob_get_clean();

		$this->consolidationHandlerType = "&lt;dataType&gt;";
		$this->textName = "<code>&lt;dataType&gt;</code>";
		$this->className = "DynamicDataConsolidator";
		$this->input = "various types of data objects";
		$this->manualUpdateCode = file_get_contents(DOCS_RESOURCES."/consolidators/dynamic-data-manual-update.html");
		$this->isSecurityData = false;
	}
}

?>
