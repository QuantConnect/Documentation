<?php
include(DOCS_RESOURCES."/datasets/research-environment/get-historical-data.php");
$createSubscriptionsLink = "/docs/v2/research-environment/datasets/equity-options#03-Create-Subscriptions";
$assetClass = "Equity Option";
$underlyingSymbolVariable = "equity_symbol";
$underlyingAssetClass = "Equity";
$getHistoricalDataText($createSubscriptionsLink, $assetClass, $underlyingSymbolVariable, $underlyingAssetClass);
?>