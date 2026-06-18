<?php
include(DOCS_RESOURCES."/consolidators/consolidator-format-info/trade-bar-definition.php");

$dataFormatInfo = new TradeBarConsolidatorFormatInfo();

$dataTypeArgC = "typeof(TradeBar)";
$dataTypeArgPy = "TradeBar";
$tickTypeArgC = "TickType.Trade";
$tickTypeArgPy = "TickType.TRADE";

$consolidatorInfo = new MarketHourAwareConsolidatorInfo($dataTypeArgC, $dataTypeArgPy, $tickTypeArgC, $tickTypeArgPy);

include(DOCS_RESOURCES."/consolidators/manage-consolidators.php");
?>
