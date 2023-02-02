<?php

if (class_exists('ConsolidatorInfo')) return;

class ConsolidatorInfo
{
	public $basedOnText, 
		$create_consolidator_resource_path,
		$define_handler_resource_path,
		$shortcut_resource_path;

	function __construct()
	{
		$this->shortcut_resource_path = "";
	}

	function get_create_consolidator_text($consolidatorClassName, $typeOf) 
	{
		ob_start();
		include(DOCS_RESOURCES.$this->create_consolidator_resource_path);
		return ob_get_clean();
	}

	function get_define_handler_text()
	{
		ob_start();
		include(DOCS_RESOURCES.$this->define_handler_resource_path);
		return ob_get_clean();
	}

	function get_shortcut_text($consolidationHandlerType)
	{
		if ($this->shortcut_resource_path == "")
		{
			return "";	
		}
		else 
		{
			ob_start();
			include(DOCS_RESOURCES.$this->shortcut_resource_path);
			return ob_get_clean();
		}
		
	}
}
?>