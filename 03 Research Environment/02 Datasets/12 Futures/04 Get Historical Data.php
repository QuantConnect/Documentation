<?php
include(DOCS_RESOURCES."/datasets/research-environment/get-historical-data.php");
$createSubscriptionsLink = "/docs/v2/research-environment/datasets/futures#03-Create-Subscriptions";
$assetClass = "Futures";
$underlyingSymbolVariable = "future.Symbol";
$underlyingAssetClass = "";
$getHistoricalDataText($createSubscriptionsLink, $assetClass, $underlyingSymbolVariable, $underlyingAssetClass);
?>
