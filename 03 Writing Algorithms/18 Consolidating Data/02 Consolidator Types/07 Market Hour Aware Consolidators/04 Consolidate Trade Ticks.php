<?php
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/tick-definition.php");

$dataFormatInfo = new TickConsolidatorFormatInfo();

$dataTypeArgC = "typeof(Tick)";
$dataTypeArgPy = "Tick";
$tickTypeArgC = "TickType.Trade";
$tickTypeArgPy = "TickType.TRADE";

$consolidatorInfo = new MarketHourAwareConsolidatorInfo($dataTypeArgC, $dataTypeArgPy, $tickTypeArgC, $tickTypeArgPy);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
