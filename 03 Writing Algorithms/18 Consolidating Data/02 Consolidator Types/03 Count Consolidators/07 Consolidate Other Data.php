<?php 
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/dynamic-data-definition.php");

$dataFormatInfo = new DynamicDataConsolidatorFormatInfo();
$consolidatorInfo = new CountConsolidatorInfo(10);
include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
<?php echo file_get_contents(DOCS_RESOURCES."/consolidators/base-data-consolidator.html"); ?>
