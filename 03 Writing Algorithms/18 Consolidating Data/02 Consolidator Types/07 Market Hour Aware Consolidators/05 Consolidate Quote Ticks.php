<?php
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/tick-quotebar-definition.php");

$dataFormatInfo = new TickQuoteBarConsolidatorFormatInfo();

$dataTypeArgC = "typeof(Tick)";
$dataTypeArgPy = "Tick";
$tickTypeArgC = "TickType.Quote";
$tickTypeArgPy = "TickType.QUOTE";

$consolidatorInfo = new MarketHourAwareConsolidatorInfo($dataTypeArgC, $dataTypeArgPy, $tickTypeArgC, $tickTypeArgPy);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
