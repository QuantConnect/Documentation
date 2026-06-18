<?php
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/quote-bar-definition.php");

$dataFormatInfo = new QuoteBarConsolidatorFormatInfo();

$dataTypeArgC = "typeof(QuoteBar)";
$dataTypeArgPy = "QuoteBar";
$tickTypeArgC = "TickType.Quote";
$tickTypeArgPy = "TickType.QUOTE";

$consolidatorInfo = new MarketHourAwareConsolidatorInfo($dataTypeArgC, $dataTypeArgPy, $tickTypeArgC, $tickTypeArgPy);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
