<?php
include(DOCS_RESOURCES."/datasets/research-environment/get-historical-data.php");
$createSubscriptionsLink = "/docs/v2/research-environment/datasets/index-options#03-Create-Subscriptions";
$assetClass = "Index Option";
$underlyingSymbolVariable = "index_symbol";
$underlyingAssetClass = "Index";
$getHistoricalDataText($createSubscriptionsLink, $assetClass, $underlyingSymbolVariable, $underlyingAssetClass);
?>